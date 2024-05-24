<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMessageRequest;
use App\Mail\SendUserEmail;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use App\Models\Schedule;
use App\Models\ScheduledUser;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class MessageController extends Controller
{
    private $botStaticId = 1010;

    public function getMessage(Message $message, Schedule $schedule)
    {

        $schedules =  $schedule->where('is_delete', 0)->get();

        $showScheds = "";

        foreach ($schedules as $schedule) {

            $slot = $schedule->sched_slot === 0 ? "Full" : $schedule->sched_slot . " slots";

            $isDisabled =  $schedule->sched_slot == 0 ? "disabled" : "";

            $showScheds .= '<button class="btn btn-outline-success m-3" id="select-sched" data-attr="' . $schedule->id . '" ' . $isDisabled . '>' . Carbon::parse($schedule->sched_date)->format('F d, Y') . '</br>' . Carbon::parse($schedule->sched_date)->format('h:i a') . ' | ' . $slot . '</button>';
        }

        $output = "";

        $messages = $message
            ->where("outgoing_msg_id", Auth::user()->id)
            ->orWhere("incoming_msg_id", Auth::user()->id)
            ->get();

        foreach ($messages as $message) {

            if ($message->outgoing_msg_id === Auth::user()->id) {

                $output .= '
                <div class="chat outgoing">
                    <div class="details">
                        <p>' . $message->msg . '</p>
                    </div>
                </div>
                ';
            } else if ($message->msg == 1) {

                $output .= '
                <div class="chat incoming">
                    <img src="' . asset("st_isidore.jpg") . '" width="60" height="60" alt="" />
                    <div class="details">
                        <p> Choose Your Schedule! </br>
                        </br>' . $showScheds . '</p>
                    </div>
                </div>
                ';
            } else {

                $output .= '
                <div class="chat incoming">
                <img src="' . asset("st_isidore.jpg") . '" width="60" height="60" alt="" />
                    <div class="details">
                        <p>'  . $message->msg . '</p>
                    </div>
                </div>
                ';
            }
        }

        return $output;
    }

    public function storeMessage(StoreMessageRequest $request, Message $message, Schedule $schedule, ScheduledUser $scheduledUser)
    {
        if ($request->sched_id) {

            $message->where("user_id", Auth::user()->id)->delete();

            $s = $schedule->find($request->sched_id);

            $reduceSlot = $s->sched_slot -= 1;

            $s->update(["sched_slot" => $reduceSlot]);

            $sched_date = Carbon::parse($s->sched_date)->format('F d, Y');

            $sched_time = Carbon::parse($s->sched_time)->format('h:i');

            $h = "
            <h1>Scheduled!</h1>
            <p>Scheduled selected on {$sched_date} at {$sched_time}</p>

            <h4>but first you need to comply this following to succesfuly booked.</h4>
                <ol>
                    <li>Pay first</li>
                    <li>Attend Seminar</li>
                    <li>Comply Requirements</li>
                </ol>
            ";

            $userName = Auth::user()->name;

            $userEmail = Auth::user()->email;

            $h1 = "
                <h1>$userName</h1>
                <h3>$userEmail</h3>
                <strong>Scheduled has been booked for $userName</strong>
                <p>Scheduled on {$sched_date} at {$sched_time}</p>
            ";

            $scheduledUser->create([
                'schedule_id' => $request->sched_id,
                'user_id' => auth()->user()->id,
                'scheduled_at' => Carbon::now(),
                'paid_at' => null,
            ]);

            Mail::to(Auth::user()->email)->send(new SendUserEmail(h: $h, s: "Confirmation of your schedule."));

            Mail::to("baptosched@gmail.com")->send(new SendUserEmail(h: $h1, s: "$userName Booked Schedule!"));
        }

        $message->create(
            [
                "user_id" => Auth::user()->id,
                "incoming_msg_id" => $this->botStaticId,
                "outgoing_msg_id" => Auth::user()->id,
                "msg" => $request->user_message,
            ]
        );

        $message->create(
            [
                "user_id" => Auth::user()->id,
                "incoming_msg_id" => Auth::user()->id,
                "outgoing_msg_id" => $this->botStaticId,
                "msg" => $request->bot_response,
            ]
        );

        if ($request->sched_id) {

            $message->create(
                [
                    "user_id" => Auth::user()->id,
                    "incoming_msg_id" => Auth::user()->id,
                    "outgoing_msg_id" => $this->botStaticId,
                    "msg" => "We sent you a message in your gmail account as proof of your booking for baptoschedule. Please check your notification. Thank you.",
                ]
            );
        }

        return Response::json(["res" => $request->user_message . " " . $request->bot_response]);
    }
}
