<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Api\LeaveController;
use App\Http\Controllers\Api\UserController;


// ================= AUTH =================
Route::post('/login', [AuthController::class, 'login']);


// ================= ABSENSI =================

// 🔥 EXPORT PDF (WAJIB PALING ATAS)
Route::get('/attendance/export-pdf', [AttendanceController::class, 'exportPDF']);

// 🔥 REKAP
Route::get('/attendance/recap', [AttendanceController::class, 'recap']);


// ================= ADMIN ABSENSI =================

// 🔥 LIST DATA ABSENSI (ADMIN)
Route::get('/attendance/admin', [AttendanceController::class, 'getAllAttendance']);

// 🔥 TAMBAH ABSENSI MANUAL
Route::post('/attendance/admin', [AttendanceController::class, 'adminStore']);


// ================= USER ABSENSI =================

// 🔥 CHECK IN / OUT
Route::post('/check-in', [AttendanceController::class, 'checkIn']);
Route::post('/check-out', [AttendanceController::class, 'checkOut']);


// ================= SCHEDULE (🔥 PALING PENTING) =================

// 🔥 LIST JADWAL
Route::get('/schedule', [AttendanceController::class, 'getSchedules']); // ⬅️ FIX NAMA METHOD

// 🔥 TAMBAH JADWAL
Route::post('/schedule', [AttendanceController::class, 'storeSchedule']);


// ================= LIST ATTENDANCE =================

// 🔥 WAJIB DI ATAS PARAMETER
Route::get('/attendance', [AttendanceController::class, 'getAllAttendance']);

// 🔥 PER USER (HARUS PALING BAWAH)
Route::get('/attendance/{user_id}', [AttendanceController::class, 'getMyAttendance']);


// ================= USER =================
Route::get('/users', [UserController::class, 'index']);
Route::post('/users', [UserController::class, 'store']);
Route::put('/users/{id}', [UserController::class, 'update']);
Route::delete('/users/{id}', [UserController::class, 'destroy']);


// ================= CUTI =================

// 🔥 TANPA PARAMETER HARUS DI ATAS
Route::get('/leave', [LeaveController::class, 'allLeave']);
Route::get('/leave/{user_id}', [LeaveController::class, 'myLeave']);

Route::post('/leave', [LeaveController::class, 'store']);
Route::post('/leave/{id}/approve', [LeaveController::class, 'approve']);
Route::post('/leave/{id}/reject', [LeaveController::class, 'reject']);
