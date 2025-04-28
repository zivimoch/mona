<?php

namespace App\Http\Controllers;

use App\Models\LogActivity;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class LogActivityController extends Controller
{
    function index()
    {
        return view('logactivity.index');
    }

    function load_data(Request $request)
    {
        $user = User::where('uuid', $request->user_id)->first();
        $data = LogActivity::select('*')
                            ->where('created_by', $user->id)
                            ->whereYear('created_at', $request->tahun)
                            ->whereMonth('created_at', $request->bulan)
                            ->orderBy('id')->get();
        foreach ($data as $datas) {
            $datas->tanggal_formatted = date('d M Y', strtotime($datas->created_at));
            $datas->jam_formatted = date('h:i:s', strtotime($datas->created_at));
        }
        return DataTables::of($data)->make(true);
    }
}
