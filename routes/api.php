<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Api\LeaveController;
use App\Http\Controllers\Api\UserController;


// ================= AUTH =================
Route::post('/login', [AuthController::class, 'login']);


// ================= ABSENSI =================

// 🔥 EXPORT PDF
Route::get(
    '/attendance/export-pdf',
    [AttendanceController::class, 'exportPDF']
);

// 🔥 REKAP
Route::get(
    '/attendance/recap',
    [AttendanceController::class, 'recap']
);


// ================= ADMIN ABSENSI =================

// 🔥 LIST DATA ABSENSI
Route::get(
    '/attendance/admin',
    [AttendanceController::class, 'getAllAttendance']
);

// 🔥 TAMBAH ABSENSI MANUAL
Route::post(
    '/attendance/admin',
    [AttendanceController::class, 'adminStore']
);


// ================= USER ABSENSI =================

// 🔥 CHECK IN
Route::post(
    '/check-in',
    [AttendanceController::class, 'checkIn']
);

// 🔥 CHECK OUT
Route::post(
    '/check-out',
    [AttendanceController::class, 'checkOut']
);


// ================= SCHEDULE =================

// 🔥 LIST JADWAL
Route::get(
    '/schedule',
    [AttendanceController::class, 'getSchedules']
);

// 🔥 TAMBAH JADWAL
Route::post(
    '/schedule',
    [AttendanceController::class, 'storeSchedule']
);

// 🔥 HAPUS JADWAL
Route::delete(
    '/schedule/{id}',
    [AttendanceController::class, 'deleteSchedule']
);


// ================= LIST ATTENDANCE =================

// 🔥 WAJIB DI ATAS PARAMETER
Route::get(
    '/attendance',
    [AttendanceController::class, 'getAllAttendance']
);

// 🔥 PER USER
Route::get(
    '/attendance/{user_id}',
    [AttendanceController::class, 'getMyAttendance']
);


// ================= USER =================

// 🔥 LIST USER
Route::get(
    '/users',
    [UserController::class, 'index']
);

// 🔥 TAMBAH USER
Route::post(
    '/users',
    [UserController::class, 'store']
);

// 🔥 UPDATE USER
Route::put(
    '/users/{id}',
    [UserController::class, 'update']
);

// 🔥 DELETE USER
Route::delete(
    '/users/{id}',
    [UserController::class, 'destroy']
);


// ================= CUTI =================

// 🔥 LIST SEMUA CUTI
Route::get(
    '/leave',
    [LeaveController::class, 'allLeave']
);

// 🔥 CUTI PER USER
Route::get(
    '/leave/{user_id}',
    [LeaveController::class, 'myLeave']
);

// 🔥 AJUKAN CUTI
Route::post(
    '/leave',
    [LeaveController::class, 'store']
);

// 🔥 APPROVE CUTI
Route::post(
    '/leave/{id}/approve',
    [LeaveController::class, 'approve']
);

// 🔥 REJECT CUTI
Route::post(
    '/leave/{id}/reject',
    [LeaveController::class, 'reject']
);
