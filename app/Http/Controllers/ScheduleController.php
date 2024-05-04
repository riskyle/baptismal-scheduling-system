<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Support\Facades\Response;

class ScheduleController extends Controller
{
    public function getSchedule(Schedule $schedule)
    {
        return Response::json(["sched" => $schedule->find(request("sched_id"))]);
        // return Response::json(["scheds" => $schedule->get()]);
    }
    public function resetSchedule(Schedule $schedule)
    {
        $slot = 5;
        $schedule->where("sched_number", 2)->update(["sched_slot" => $slot]);
        $schedule->where("sched_number", 4)->update(["sched_slot" => $slot]);
        return Response::json(["status" => "Done reset!"]);
    }
}
