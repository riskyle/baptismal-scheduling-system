<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\ScheduledUser;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class ScheduleController extends Controller
{
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

    public function clientScheduled(ScheduledUser $scheduledUser)
    {

        $scheduledUsers = $scheduledUser->all();

        return view('admin.client-scheduled', compact('scheduledUsers'));
    }

    public function paid(ScheduledUser $scheduledUser)
    {

        $scheduledUser->update(['paid_at' => Carbon::now()]);

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

    public function getSchedule(Schedule $schedule)
    {
        return Response::json(["sched" => $schedule->find(request("sched_id"))]);
    }

    public static function preventUserSpamming()
    {

        $scheduledUsers = new ScheduledUser;

        $schedules = new Schedule;

        $carbon = new Carbon;

        $currentDate = $carbon->now();

        $thresholdDate = $currentDate->subHours(24)->format('Y-m-d H:i:s');

        $user = $scheduledUsers->where('user_id', 1)->first();

        dump($thresholdDate);
        dump($user->scheduled_at);

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
}
