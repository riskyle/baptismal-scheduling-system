<?php

use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ScheduleController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect('/login'));
Route::middleware(["auth", "verified"])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    // AJAX 
    Route::post("/add-sched", [ScheduleController::class, "addSchedule"]);
    Route::get("/get-sched", [ScheduleController::class, "getSchedule"]);
    Route::get("/get-msg", [MessageController::class, "getMessage"]);
    Route::post("/store-msg", [MessageController::class, "storeMessage"]);
    Route::get("/reset-sched", [ScheduleController::class, "resetSchedule"]);
});
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
require __DIR__ . '/auth.php';
