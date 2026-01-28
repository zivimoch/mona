<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class SSOClientController extends Controller
{
    public function consume(Request $request)
    {
        try {
            $data = explode('|', decrypt($request->token ?? ''));
        } catch (\Exception $e) {
            return abort(403, 'Invalid token');
        }

        if (!$data || count($data) !== 10) return abort(403, 'Malformed token');

        $email = $data[0];

        $user = User::where('email', $email)->first();

        if (!$user) {
            // User baru, buat akun default
            $user = User::create([
                'name' => $data[6],
                'email' => $email,
                'password' => Hash::make(Str::random(12)), // password random
                'jabatan' => $data[1],
                'active' => $data[2], 
                'deleted_at' => NULL,
                'kotkab_id' => $data[4],
                'supervisor_id' => $data[5],
                'kantor_latitude' => $data[7],
                'kantor_longitude' => $data[8],
            ]);
        }

        Auth::login($user);

        return response()->json(['success' => true]);
    }
}
