<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMessageRequest;
use App\Mail\SendUserEmail;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class MessageController extends Controller
{
    private $botStaticId = 1010;
    public function getMessage(Message $message, Schedule $schedule)
    {
        $schedules =  $schedule->get();
        $dis = "";
        $i = 0;
        foreach ($schedules as $schedule) {
            $i += 1;
            $slot = $schedule->sched_slot === 0 ? "Full" : $schedule->sched_slot . " slots";
            $everySundayOf = $schedule->sched_number === 2 ? "Second" : "Fourth";
            $ipakitani = "";
            if ($i === 1) {
                $ipakitani .= "<span><strong><small>$everySundayOf Sunday of the Month</small></strong></span>";
            } else if ($i === 3) {
                $ipakitani .= "</br><span><strong><small>$everySundayOf  Sunday of the Month</small></strong></span>";
            }
            $dis .= $ipakitani . '<button class="btn btn-outline-success m-3" id="select-sched" data-attr="' . $schedule->id . '">' . Carbon::createFromFormat('H:i:s', $schedule->sched_time)->format('h:i A') . ' |' . $slot . '</button>';
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
                        </br>' . $dis . '</p>
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
    public function storeMessage(StoreMessageRequest $request, Message $message, Schedule $schedule)
    {
        if ($request->sched_id) {
            $message->where("user_id", Auth::user()->id)->delete();
            $s = $schedule->find($request->sched_id);
            $reduceSlot = $s->sched_slot -= 1;
            $s->update(["sched_slot" => $reduceSlot]);
            $schedFormattedTime = Carbon::createFromFormat('H:i:s', $s->sched_time)->format('h:i A');
            $idk = $s->sched_number == 2 ? "Second Sunday of the Month" : "Fourth Sunday of the Month";
            $h = "
            <h1>Scheduled!</h1>
            <strong>Your scheduled has been booked!</strong>
            <p>Slot Time: {$schedFormattedTime}</p>
            <p>{$idk}</p>
            ";
            $userName = Auth::user()->name;
            $userEmail = Auth::user()->email;
            $h1 = "
                <h1>$userName</h1>
                <h3>$userEmail</h3>
                <strong>Scheduled has been booked for $userName</strong>
                <p>Slot Time: {$schedFormattedTime}</p>
                <p>{$idk}</p>
            ";
            Mail::to(Auth::user()->email)->send(new SendUserEmail(h: $h, s: "To verify that you are scheduled."));
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
