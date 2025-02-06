<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class RekapController extends Controller
{
    public function index() {
        
    }

    public function detail_user() {
        return view('rekap.absen_users');
    }

    public function load_detail_user(Request $request){
        $datas = DB::table('users as a')
        ->selectRaw(
            "a.name, a.jabatan, c.judul as rules, b.tanggal_masuk, b.tanggal_pulang, 
            b.jam_masuk, b.jam_pulang, b.jarak_masuk, b.jarak_pulang, b.catatan_masuk, b.catatan_pulang,
            SUM(CASE 
                WHEN c.jam_masuk = '00:00:00' 
                    AND TIME(b.jam_masuk) >= '23:00:00' THEN 0
                WHEN TIMESTAMPDIFF(MINUTE, c.jam_masuk, b.jam_masuk) > 60 THEN 
                    TIMESTAMPDIFF(MINUTE, c.jam_masuk, b.jam_masuk) - 60
                ELSE 0 
            END) AS menit_terlambat
            "
        )
        ->leftJoin('absen as b', 'a.id', '=', 'b.user_id')
        ->leftJoin('shift_rules as c', 'c.kode', '=', 'b.kode_shift_rules')
        ->whereYear('b.tanggal_masuk', '=', $request->tahun)
        ->whereMonth('b.tanggal_masuk', '=', $request->bulan)
        ->where('b.user_id', '=', Auth::user()->id)
        ->groupBy(
            'b.id', 
            'a.name', 
            'a.jabatan', 
            'c.judul', 
            'b.tanggal_masuk', 
            'b.tanggal_pulang', 
            'b.jam_masuk', 
            'b.jam_pulang', 
            'b.jarak_masuk', 
            'b.jarak_pulang', 
            'b.catatan_masuk', 
            'b.catatan_pulang'
        )
        ->get();

    
        return DataTables::of($datas)->make(true);
    }
}
