<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    // ================= LIST USER =================
    public function index()
    {
        return response()->json([

            'data' => User::select(

                'id',
                'name',
                'email',
                'role'

            )->orderBy('id', 'desc')->get()
        ]);
    }

    // ================= TAMBAH USER =================
    public function store(Request $request)
    {
        // 🔥 VALIDASI
        $validator = Validator::make(

            $request->all(),

            [

                'name' =>
                    'required',

                'email' =>
                    'required|email|unique:users,email',

                'password' =>
                    'required|min:4',

                'role' =>
                    'required',
            ]
        );

        if ($validator->fails()) {

            return response()->json([

                'message' =>
                    $validator->errors()->first()

            ], 422);
        }

        $user = User::create([

            'name' =>
                $request->name,

            'email' =>
                $request->email,

            'password' =>
                Hash::make(
                    $request->password
                ),

            'role' =>
                $request->role
        ]);

        return response()->json([

            'message' =>
                'User berhasil ditambahkan',

            'data' =>
                $user
        ]);
    }

    // ================= UPDATE USER =================
    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {

            return response()->json([

                'message' =>
                    'User tidak ditemukan'

            ], 404);
        }

        // 🔥 VALIDASI
        $validator = Validator::make(

            $request->all(),

            [

                'name' =>
                    'required',

                'email' =>
                    'required|email|unique:users,email,' . $id,

                'role' =>
                    'required',
            ]
        );

        if ($validator->fails()) {

            return response()->json([

                'message' =>
                    $validator->errors()->first()

            ], 422);
        }

        $user->update([

            'name' =>
                $request->name,

            'email' =>
                $request->email,

            'role' =>
                $request->role
        ]);

        return response()->json([

            'message' =>
                'User berhasil diupdate',

            'data' =>
                $user
        ]);
    }

    // ================= DELETE USER =================
    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {

            return response()->json([

                'message' =>
                    'User tidak ditemukan'

            ], 404);
        }

        $user->delete();

        return response()->json([

            'message' =>
                'User berhasil dihapus'
        ]);
    }

    // ================= UPDATE PROFILE SENDIRI =================
    public function updateProfile(
        Request $request,
        $id
    ) {

        $user = User::find($id);

        if (!$user) {

            return response()->json([

                'message' =>
                    'User tidak ditemukan'

            ], 404);
        }

        // 🔥 VALIDASI
        $validator = Validator::make(

            $request->all(),

            [

                'name' =>
                    'required',

                'email' =>
                    'required|email|unique:users,email,' . $id,
            ]
        );

        if ($validator->fails()) {

            return response()->json([

                'message' =>
                    $validator->errors()->first()

            ], 422);
        }

        // 🔥 UPDATE DATA
        $user->name =
            $request->name;

        $user->email =
            $request->email;

        // 🔥 PASSWORD OPSIONAL
        if (

            $request->password != null &&
            $request->password != ''

        ) {

            $user->password =
                Hash::make(
                    $request->password
                );
        }

        $user->save();

        return response()->json([

            'message' =>
                'Profile berhasil diupdate',

            'user' => [

                'id' =>
                    $user->id,

                'name' =>
                    $user->name,

                'email' =>
                    $user->email,

                'role' =>
                    $user->role,
            ]
        ]);
    }
}