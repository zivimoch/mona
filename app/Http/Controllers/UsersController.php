<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Exception;
use Validator;

class UsersController extends Controller
{
    function index()
    {
        return view('users.index');
    }

    function load_data()
    {
        $data = User::all();
        return DataTables::of($data)->make(true);
    }

    function show(Request $request)
    {
        $data = User::select('*')
                    ->where('uuid', $request->uuid)
                    ->first();
        return response()->json($data);
    }

    function store (Request $request) {
        try {
            $validator = Validator::make($request->all(), [
                ]);
                if ($validator->fails())
                {
                    throw new Exception($validator->errors());
                }

                $user = User::where('uuid', $request->uuid)->first();
                if ($user == null) {
                    throw new Exception('Data tidak ditemukan!');
                }
                
                $data = [
                    // 'name' => $request->name,
                    // 'email' => $request->email,
                    // 'jabatan' => $request->jabatan,
                    // 'alamat' => $request->alamat,
                    // 'no_telp' => $request->no_telp,
                    'kantor_latitude' => $request->kantor_latitude,
                    'kantor_longitude' => $request->kantor_longitude,
                    'sisa_cuti' => $request->sisa_cuti,
                ];
                // dd($data)   ;
                
            $proses = User::where('uuid', $request->uuid)->update($data);

            //return response
            return response()->json([
                'success' => true,
                'code'    => 200,
                'message' => 'Data Berhasil Disimpan!',
                'uuid' => $proses->uuid
            ]);
        } catch (Exception $e){
            return response()->json(['message' => $e->getMessage()]);
            die();
        }
    }
}
