<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Api\LeaveController;
use App\Http\Controllers\Api\UserController;


// ================= AUTH =================

// 🔥 LOGIN
Route::post(
    '/login',
    [AuthController::class, 'login']
);


// ================= ABSENSI =================

// 🔥 REKAP (WAJIB DI ATAS PARAMETER)
Route::get(
    '/attendance/recap',
    [AttendanceController::class, 'recap']
);

// 🔥 EXPORT PDF
Route::get(
    '/attendance/export-pdf',
    [AttendanceController::class, 'exportPDF']
);

// 🔥 LIST SEMUA ABSENSI
Route::get(
    '/attendance',
    [AttendanceController::class, 'getAllAttendance']
);

// 🔥 LIST ABSENSI ADMIN
Route::get(
    '/attendance/admin',
    [AttendanceController::class, 'getAllAttendance']
);

// 🔥 ABSENSI PER USER (WAJIB PALING BAWAH)
Route::get(
    '/attendance/{user_id}',
    [AttendanceController::class, 'getMyAttendance']
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


// ================= PROFILE =================

// 🔥 UPDATE PROFILE SENDIRI
Route::post(
    '/profile/update/{id}',
    [UserController::class, 'updateProfile']
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