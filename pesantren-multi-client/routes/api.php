<?php

use App\Http\Controllers\Api\AttendanceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\GateAttendanceController;
use App\Http\Controllers\Api\PermitController;

Route::prefix('attendance')->group(function () {
    Route::post('/scan',  [AttendanceController::class, 'scan']);
    Route::get('/today',  [AttendanceController::class, 'today']);
});

Route::get('/students/{student}/attendance', [AttendanceController::class, 'studentHistory']);

Route::prefix('gate')->group(function () {
    Route::post('/scan', [GateAttendanceController::class, 'scan']);
    Route::get('/currently-outside', [GateAttendanceController::class, 'currentlyOutside']);
    Route::get('/history/{student}', [GateAttendanceController::class, 'history']);
});

Route::prefix('permits')->group(function () {
    Route::post('/initiate', [PermitController::class, 'initiate']);  // UC-01
    Route::post('/depart',   [PermitController::class, 'depart']);    // UC-02
    Route::post('/report',   [PermitController::class, 'report']);    // UC-03
});
