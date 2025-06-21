<?php

namespace App\Http\Controllers;

use App\Models\PerbaikanAbsen;
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

    function load_rekap(Request $request) {
        $user = User::where('uuid', $request->uuid)->first();
        $datas = DB::table('users as a')
                ->leftJoin('absen as b', 'a.id', 'b.user_id')
                ->whereYear('b.tanggal_masuk', $request->tahun)
                ->whereMonth('b.tanggal_masuk', $request->bulan)
                ->selectRaw('
                a.uuid, a.name, a.jabatan,
                SUM(
                    CASE WHEN b.jarak_masuk > 100 THEN 1 ELSE 0 END + 
                    CASE WHEN b.jarak_pulang > 100 THEN 1 ELSE 0 END
                ) AS diluar_radius,
                COUNT(DISTINCT b.id) AS total_hari,
                SUM(b.menit_telat) AS total_menit_telat,
                SUM(CASE WHEN b.tanggal_pulang IS NULL AND kategori != "cuti" THEN 1 ELSE 0 END) AS tidak_absen_pulang,
                0 AS total_menit_pulang_awal,
                FLOOR(COALESCE(SUM(b.menit_telat), 0) / 480) AS total_hari_telat
            ')->groupBy('a.id', 'a.uuid', 'a.name', 'a.jabatan');
            

        if (isset($request->uuid)) {
            $datas = $datas->where('a.id', $user->id)->first();
            return response()->json($datas);
        } else {
            $datas = $datas->get();
            return DataTables::of($datas)->make(true);
        }

    }

    // function load_rekap_user(Request $request) {
    //     $results = DB::table('users as a')
    //             ->leftJoin('absen as b', 'a.id', 'b.user_id')
    //             ->whereYear('b.tanggal_masuk', $request->tahun)
    //             ->whereMonth('b.tanggal_masuk', $request->bulan)
    //             ->where('b.user_id', '=', Auth::user()->id)
    //             ->selectRaw('
    //                 COUNT(DISTINCT b.id) AS total_hari,
    //                 SUM(b.menit_telat) AS total_menit_telat,
    //                 SUM(CASE 
    //                             WHEN b.jarak_masuk > 100 THEN 1 
    //                             ELSE 0 
    //                         END + 
    //                         CASE 
    //                             WHEN b.jarak_pulang > 100 THEN 1 
    //                             ELSE 0 	
    //                         END) AS diluar_radius
    //             ')
    //             ->first();

    //     return response()->json($results);
    // }

    public function load_detail_user(Request $request){
        $user = User::where('uuid', $request->user_id)->first();
        $datas = DB::table('users as a')
        ->selectRaw("
            a.name, a.jabatan, 
            IF(c.judul IS NOT NULL, c.judul, 'cuti') AS rules, 
            b.tanggal_masuk, b.tanggal_pulang, 
            b.jam_masuk, b.jam_pulang, 
            b.jarak_masuk, b.jarak_pulang, 
            b.catatan_masuk, b.catatan_pulang, 
            b.menit_telat AS menit_terlambat,
            GROUP_CONCAT(DISTINCT 
                CONCAT(d.uuid, ':', IFNULL(d.tipe_absen, '-'), ':', IFNULL(d.disetujui, '-'))
                ORDER BY d.id ASC
            ) AS perbaikans
        ")
        ->leftJoin('absen as b', 'a.id', '=', 'b.user_id')
        ->leftJoin('shift_rules as c', 'c.kode', '=', 'b.kode_shift_rules')
        ->leftJoin('perbaikan_absen as d', 'd.absen_id', '=', 'b.id')
        ->whereYear('b.tanggal_masuk', '=', $request->tahun)
        ->whereMonth('b.tanggal_masuk', '=', $request->bulan)
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
            'b.catatan_pulang',
            'b.menit_telat'
        );
        if (isset($request->user_id)) {
            // untuk melihat detail absen user
            $datas = $datas->where('b.user_id', '=', $user->id);
        } else {
            // untuk melihat perbaikan absen
            $datas = $datas->whereNotNull('d.absen_id')->orderBy('b.id', 'desc');
        }
        $datas = $datas->get();
    
        return DataTables::of($datas)->make(true);
    }
}
