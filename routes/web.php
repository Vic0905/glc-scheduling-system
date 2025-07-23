<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/schedules/report', [ScheduleController::class, 'generateReport'])->name('schedules.report');

Route::middleware('auth')->group(function () {

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Dashboard activity logs
    Route::delete('/activity-logs', [DashboardController::class, 'deleteLogs'])->name('activity-logs.delete');

    // CRUD resource routes
    Route::resource('students', StudentController::class);
    Route::resource('teachers', TeacherController::class);
    Route::resource('rooms', RoomController::class);
    Route::resource('subjects', SubjectController::class);
    Route::resource('users', UserController::class);

    // Custom schedule routes
    Route::get('/schedules/input', [ScheduleController::class, 'input'])->name('schedules.input');
    Route::get('/schedules/available', [ScheduleController::class, 'available'])->name('schedules.available');
    Route::post('/schedules/add', [ScheduleController::class, 'addStudentToSchedule'])->name('schedules.add');
    Route::patch('/schedules/{id}/status', [ScheduleController::class, 'updateStatus'])->name('schedules.updateStatus');
    Route::delete('/schedules/delete-room-date/{room}/{date}', [ScheduleController::class, 'destroyByRoomAndDate'])->name('schedules.deleteByRoomAndDate');
    // routes/web.php

    // Teacher-student schedule route
    Route::get('/teachers/{teacherId}/students/{scheduleDate}', [ScheduleController::class, 'showTeacherStudents'])->name('teachers.students');
    Route::get('/teachers/{teacher}/students/{scheduleDate}', [TeacherController::class, 'getStudents']);

    // Schedule resource routes (make sure custom routes above are not overwritten)
    Route::resource('schedules', ScheduleController::class);
    Route::put('schedules/{id}', [ScheduleController::class, 'update'])->name('schedules.update');
    Route::delete('/schedules/{id}', [ScheduleController::class, 'destroy'])->name('schedules.destroy');

    // User management routes
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
});

require __DIR__.'/auth.php';
