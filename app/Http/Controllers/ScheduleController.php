<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMessageRequest;
use App\Mail\SendUserEmail;
use App\Models\Message;
use App\Models\Schedule;
use App\Models\ScheduledUser;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;

class ScheduleController extends Controller
{
    private int $month = 0;
    public function index(Schedule $schedule)
    {

        $schedules = $schedule->where('is_delete', 0)->get();

        return view('admin.schedule.index', compact('schedules'));
    }

    public function create()
    {
        return view('admin.schedule.create');
    }

    public function store(Request $request, Schedule $schedule)
    {

        $inputtedData = [
            'sched_date' => $request->sched_date,
            'sched_slot' => $request->sched_slot,
            'is_delete' => 0,
        ];

        $schedule->create($inputtedData);

        return redirect(route('admin.schedules'));
    }

    public function edit(Schedule $schedule, Request $request)
    {
        return view('admin.schedule.edit', compact('schedule'));
    }

    public function delete(Schedule $schedule)
    {

        $schedule->update(['is_delete' => 1]);

        return redirect(route('admin.schedules'));
    }

    public function update(Schedule $schedule, Request $request)
    {

        $schedule->update($request->all());

        return redirect(route('admin.schedules'));
    }

    public function clientScheduled(ScheduledUser $scheduledUser, Request $request)
    {
        $scheduledUsers = $scheduledUser->all();

        if ($request->month) {
            $this->month = $request->month;
            $scheduledUsers = $get = ScheduledUser::whereHas('schedule', function ($query) {
                $query->whereMonth('sched_date', $this->month);
            })->get();
        }

        return view('admin.client-scheduled', compact('scheduledUsers'));
    }

    public function paid(ScheduledUser $scheduledUser)
    {

        $name = $scheduledUser->user->name;

        $carbon = new Carbon;

        $datePaid = $carbon->parse($carbon->now())->format('F d, Y');

        $h = "
            <h1>Your Paid</h1>
            <h3>Paid at $datePaid</h3>
            <strong>Scheduled has been paid for $name</strong>
            <p>You paid 560 pesos cash</p>
        ";

        $scheduledUser->update(['paid_at' => Carbon::now()]);

        Mail::to($scheduledUser->user->email)->send(new SendUserEmail(h: $h, s: "Your Paid"));

        return redirect(route('admin.client-scheduled'));
    }

    public function deleteClientScheduled(ScheduledUser $scheduledUser, Schedule $schedule)
    {

        $getSchedule =  $schedule->find($scheduledUser->schedule_id);

        $retrieveSlot = $getSchedule->sched_slot += 1;

        $schedule->find($scheduledUser->schedule_id)->update(['sched_slot' => $retrieveSlot]);

        $scheduledUser->delete();

        return redirect(route('admin.client-scheduled'));
    }

    public function getSchedule(Schedule $schedule, ScheduledUser $scheduledUser)
    {
        // $schedule =  $schedule->find(request("sched_id"));
        $sU =  $scheduledUser
                ->where('user_id', auth()->user()->id)
                ->where('is_cancel', 0)->count();
        if($sU){
            return Response::json(["status" => true]);  
        }
        return Response::json(["sched" => $schedule->find(request("sched_id"))]);
    }

    public static function preventUserSpamming()
    {
        $scheduledUsers = new ScheduledUser;

        $schedules = new Schedule;

        $carbon = new Carbon;

        $currentDate = $carbon->now();

        $thresholdDate = $currentDate->subHours(24)->format('Y-m-d H:i:s');

        $getScheduledUser = $scheduledUsers->where('paid_at', null)->get();

        if ($getScheduledUser) {

            foreach ($getScheduledUser as $scheduledUser) {

                if ($scheduledUser->scheduled_at <  $thresholdDate) {

                    $getSchedules = $schedules->where('id', $scheduledUser->schedule_id)->first();

                    $retrieveSlot = $getSchedules->sched_slot += 1;

                    $getSchedules->update(['sched_slot' => $retrieveSlot]);
                }
            }
        }

        $scheduledUsers
            ->where('scheduled_at', '<', $thresholdDate)
            ->where('paid_at', null)
            ->delete();
    }

    public function checkList(ScheduledUser $scheduledUser, Request $request, Message $message)
    {
        $scheduledUser = $scheduledUser->where('id', $request->id);

        $name = $scheduledUser->first()->user->name;

        $email = $scheduledUser->first()->user->email;

        if ($request->purpose == 0) {
            $scheduledUser = $scheduledUser->first();
        } else if ($request->purpose == 1) {

            $carbon = new Carbon;

            $scheduledUser->update(['paid_at' => $carbon->now()]);

            $datePaid = $carbon->parse($carbon->now())->format('F d, Y');

            $h = "
            <h1>Your Paid</h1>
            <h3>Paid at $datePaid</h3>
            <strong>Scheduled has been paid</strong>
            <p>You paid 560 pesos cash</p>";

            $s = "Confirmation of your schedule.";

            Mail::to($email)->send(new SendUserEmail(h: $h, s: $s));
        } else if ($request->purpose == 2) {
            $scheduledUser->update(['is_seminar' => 1]);
            $h = "
            <p>Your done with your seminar, Thank you for your cooperation</p>";

            $s = "Attendee of Seminar";

            Mail::to($email)->send(new SendUserEmail(h: $h, s: $s));
        } else if ($request->purpose == 3) {
            $scheduledUser->update(['is_requirements' => 1]);
            $h = "<p>Requirements has been completed, Thank you for your cooperation.</p>";

            $s = "Completed Requirements";

            Mail::to($email)->send(new SendUserEmail(h: $h, s: $s));
        } else if ($request->purpose == 4) {

            $paid = $scheduledUser->first()->paid_at;

            $seminar = $scheduledUser->first()->is_seminar;

            $requirements = $scheduledUser->first()->is_requirements;

            $userId = $scheduledUser->first()->user->id;

            $sched_date = Carbon::parse($scheduledUser->first()->schedule->sched_date)->format('F d, Y');

            $sched_time = Carbon::parse($scheduledUser->first()->schedule->sched_date)->format('h:i a');

            if ($paid != null && $seminar == 1 && $requirements == 1) {
                $h = "<h1>Scheduled Booked!</h1>
                <p>Scheduled has been booked on {$sched_date} at {$sched_time}</p>
                ";

                $s = "Scheduled has been finally booked";

                $message->create(
                    [
                        "user_id" => $userId,
                        "incoming_msg_id" => $userId,
                        "outgoing_msg_id" => 1010,
                        "msg" => "Your Schedule on {$sched_date} at {$sched_time} has been booked confirmed.",
                    ]
                );

                $scheduledUser->update(['is_confirmed' => 1]);

                Mail::to($email)->send(new SendUserEmail(h: $h, s: $s));
            }
        }

        return Response::json(['scheduledUser' => $scheduledUser]);
    }

    public function cancel($id, ScheduledUser $scheduledUser){
        $scheduledUser->where('id', $id)->update(['is_cancel' => 1]);
        return redirect(route('admin.client-scheduled'));
    }
}
