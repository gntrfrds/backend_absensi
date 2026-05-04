<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{

    // ================= GET ALL =================
    public function getAllAttendance()
    {
        $data = DB::table('attendances')
            ->join('users', 'attendances.user_id', '=', 'users.id')
            ->select(
                'attendances.*',
                'users.name',
                'users.role'
            )
            ->orderBy('attendances.date', 'desc')
            ->get();

        return response()->json([
            'data' => $data
        ]);
    }

    // ================= GET PER USER =================
    public function getMyAttendance($user_id)
    {
        $data = DB::table('attendances')
            ->where('user_id', $user_id)
            ->orderBy('date', 'desc')
            ->get();

        return response()->json([
            'data' => $data
        ]);
    }

    // ================= CHECK IN =================
    public function checkIn(Request $request)
    {
        try {

            $request->validate([
                'user_id' => 'required|exists:users,id',
                'photo' => 'required|image'
            ]);

            $today = now()->toDateString();
            $now = now()->format('H:i:s');

            // 🔥 CEK SUDAH ABSEN
            $cek = DB::table('attendances')
                ->where('user_id', $request->user_id)
                ->whereDate('date', $today)
                ->first();

            if ($cek) {
                return response()->json([
                    'message' => 'Sudah check-in hari ini'
                ], 400);
            }

            // 🔥 CEK JADWAL
            $schedule = DB::table('schedules')
                ->where('user_id', $request->user_id)
                ->whereDate('date', $today)
                ->first();

            if (!$schedule) {
                return response()->json([
                    'message' => 'Belum ada jadwal hari ini'
                ], 400);
            }

            // 🔥 HITUNG TELAT
            $late = max(0, strtotime($now) - strtotime($schedule->start_time)) / 60;
            $status = $late > 0 ? 'late' : 'present';

            // 🔥 UPLOAD FOTO
            $path = $request->file('photo')->store('checkin_photos', 'public');

            // 🔥 SIMPAN
            DB::table('attendances')->insert([
                'user_id' => $request->user_id,
                'date' => $today,
                'check_in' => now(),
                'check_in_photo' => $path,
                'check_in_status' => $status,
                'late_minutes' => $late,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json([
                'message' => 'Check-in berhasil',
                'status' => $status,
                'late_minutes' => $late
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'error' => $e->getMessage(),
                'line' => $e->getLine()
            ], 500);
        }
    }

    // ================= CHECK OUT =================
    public function checkOut(Request $request)
    {
        try {

            $request->validate([
                'user_id' => 'required|exists:users,id',
                'photo' => 'required|image'
            ]);

            $today = now()->toDateString();
            $now = now()->format('H:i:s');

            // 🔥 AMBIL DATA ABSEN
            $attendance = DB::table('attendances')
                ->where('user_id', $request->user_id)
                ->whereDate('date', $today)
                ->first();

            if (!$attendance) {
                return response()->json([
                    'message' => 'Belum check-in'
                ], 400);
            }

            if ($attendance->check_out) {
                return response()->json([
                    'message' => 'Sudah check-out'
                ], 400);
            }

            // 🔥 CEK JADWAL
            $schedule = DB::table('schedules')
                ->where('user_id', $request->user_id)
                ->whereDate('date', $today)
                ->first();

            if (!$schedule) {
                return response()->json([
                    'message' => 'Jadwal tidak ditemukan'
                ], 400);
            }

            // 🔥 STATUS
            $early = strtotime($now) < strtotime($schedule->end_time);
            $status = $early ? 'early_leave' : 'normal';

            // 🔥 UPLOAD FOTO
            $path = $request->file('photo')->store('checkout_photos', 'public');

            // 🔥 UPDATE
            DB::table('attendances')
                ->where('id', $attendance->id)
                ->update([
                    'check_out' => now(),
                    'check_out_photo' => $path,
                    'check_out_status' => $status,
                    'updated_at' => now(),
                ]);

            return response()->json([
                'message' => 'Check-out berhasil',
                'status' => $status
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'error' => $e->getMessage(),
                'line' => $e->getLine()
            ], 500);
        }
    }

    // ================= SCHEDULE LIST =================
    public function getSchedule()
    {
        $data = DB::table('schedules')
            ->join('users', 'schedules.user_id', '=', 'users.id')
            ->select(
                'schedules.*',
                'users.name'
            )
            ->orderBy('date', 'desc')
            ->get();

        return response()->json([
            'data' => $data
        ]);
    }

    // ================= TAMBAH SCHEDULE =================
    public function storeSchedule(Request $request)
    {
        try {

            $request->validate([
                'user_id' => 'required|exists:users,id',
                'date' => 'required|date',
                'shift' => 'required|in:morning,night',
                'start_time' => 'required',
                'end_time' => 'required',
                'created_by' => 'required|exists:users,id'
            ]);

            // 🔥 VALIDASI ROLE
            $creator = DB::table('users')->where('id', $request->created_by)->first();
            if (!in_array($creator->role, ['admin', 'operator'])) {
                return response()->json([
                    'message' => 'Hanya admin/operator'
                ], 403);
            }

            $user = DB::table('users')->where('id', $request->user_id)->first();
            if (!in_array($user->role, ['employee', 'intern'])) {
                return response()->json([
                    'message' => 'User harus employee/intern'
                ], 400);
            }

            DB::table('schedules')->insert([
                'user_id' => $request->user_id,
                'day_name' => date('l', strtotime($request->date)),
                'date' => $request->date,
                'shift' => $request->shift,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'created_by' => $request->created_by,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json([
                'message' => 'Jadwal berhasil dibuat'
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'error' => $e->getMessage(),
                'line' => $e->getLine()
            ], 500);
        }
    }

}
