<?php

use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ScheduleController;
use Illuminate\Support\Facades\Route;

Route::middleware(["auth", "verified"])->group(function () {
    Route::get('/', function () {
        return view('dashboard');
    })->name('dashboard')->middleware('user');

    Route::middleware('admin')->prefix('admin')->group(function () {
        Route::get('/', fn () => view('admin.index'))->name('admin.index');
        Route::get('schedules', [ScheduleController::class, 'index'])->name('admin.schedules');
        Route::get('scheduled-clients', [ScheduleController::class, 'clientScheduled'])->name('admin.client-scheduled');
        Route::get('schedule/create', [ScheduleController::class, 'create'])->name('admin.schedule.create');
        Route::get('schedules/{schedule}/edit', [ScheduleController::class, 'edit'])->name('admin.schedule.edit');

        Route::post('schedules/store', [ScheduleController::class, 'store'])->name('admin.schedule.store');
        Route::patch('schedules/{schedule}/update', [ScheduleController::class, 'update'])->name('admin.schedule.update');
        Route::patch('paid/{scheduledUser}', [ScheduleController::class, 'paid'])->name('admin.client-scheduled.paid');
        Route::patch('schedule/{schedule}/delete', [ScheduleController::class, 'delete'])->name('admin.schedule.delete');
        Route::delete('schedule/{scheduledUser}/delete', [ScheduleController::class, 'deleteClientScheduled'])->name('admin.client-schedule.delete');
    });

    // AJAX 
    Route::post("/add-sched", [ScheduleController::class, "addSchedule"]);
    Route::get("/get-sched", [ScheduleController::class, "getSchedule"]);
    Route::get("/get-msg", [MessageController::class, "getMessage"]);
    Route::post("/store-msg", [MessageController::class, "storeMessage"]);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
