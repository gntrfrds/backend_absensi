<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LeaveController extends Controller
{
    // ================= AJUKAN CUTI =================
    public function store(Request $request)
    {
        $filePath = null;

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $filePath = $file->store('leave_files', 'public');
        }

        $id = DB::table('leaves')->insertGetId([
            'user_id' => $request->user_id,
            'type' => $request->type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'reason' => $request->reason,
            'attachment' => $filePath,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $data = DB::table('leaves')->where('id', $id)->first();

        return response()->json([
            'message' => 'Pengajuan cuti berhasil',
            'data' => $data
        ]);
    }

    // ================= CUTI SENDIRI =================
    public function myLeave($user_id)
    {
        $data = DB::table('leaves')
            ->where('user_id', $user_id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'data' => $data
        ]);
    }

    // ================= SEMUA CUTI =================
    public function allLeave()
    {
        $data = DB::table('leaves')
            ->join('users', 'users.id', '=', 'leaves.user_id')
            ->select(
                'leaves.*',
                'users.name'
            )
            ->orderBy('leaves.created_at', 'desc')
            ->get();

        return response()->json([
            'data' => $data
        ]);
    }

    // ================= APPROVE =================
    public function approve($id, Request $request)
    {
        DB::table('leaves')
            ->where('id', $id)
            ->update([
                'status' => 'approved',
                'approved_by' => $request->approved_by,
                'approved_at' => now(),
                'updated_at' => now(),
            ]);

        return response()->json([
            'message' => 'Cuti disetujui'
        ]);
    }

    // ================= REJECT =================
    public function reject($id, Request $request)
    {
        DB::table('leaves')
            ->where('id', $id)
            ->update([
                'status' => 'rejected',
                'approved_by' => $request->approved_by,
                'approved_at' => now(),
                'rejection_reason' => $request->reason,
                'updated_at' => now(),
            ]);

        return response()->json([
            'message' => 'Cuti ditolak'
        ]);
    }
}
