<?php

namespace App\Http\Controllers;

use App\Models\PerbaikanAbsen;
use App\Models\ShiftRules;
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
        $rules = ShiftRules::whereNull('deleted_at')->get();

        return view('rekap.absen_users')->with('rules', $rules);
    }

    function load_rekap(Request $request) {
        $year = $request->tahun ?? date('Y');

        $user = User::where('uuid', $request->uuid)->first();
        $datas = DB::table('users as a')
                ->leftJoin('absen as b', 'a.id', 'b.user_id')
                ->whereYear('b.tanggal_masuk', $year)
                ->whereNull('b.deleted_at')
                ->selectRaw('
                a.uuid, a.name, a.wilayah, a.penempatan, a.jabatan, a.email,
                ROUND(
                    SUM(
                    TIMESTAMPDIFF(
                        MINUTE, 
                        CONCAT(b.tanggal_masuk, " ", b.jam_masuk),
                        CONCAT(
                        IF(b.kode_shift_rules IN (1, 2, 7, 8) AND 
                            TIMESTAMPDIFF(DAY, b.tanggal_masuk, b.tanggal_pulang) > 0
                            , b.tanggal_masuk, b.tanggal_pulang),
                        " ",
                        b.jam_pulang
                        )
                    )
                    ), 0
                ) AS total_menit_hadir,
                ROUND(
                    AVG(
                    TIMESTAMPDIFF(
                        MINUTE, 
                        CONCAT(b.tanggal_masuk, " ", b.jam_masuk),
                        CONCAT(
                        IF(b.kode_shift_rules IN (1, 2, 7, 8) AND 
                            TIMESTAMPDIFF(DAY, b.tanggal_masuk, b.tanggal_pulang) > 0
                            , b.tanggal_masuk, b.tanggal_pulang),
                        " ",
                        b.jam_pulang
                        )
                    )
                    ), 0
                ) AS rata_menit_hadir,
                SUM(
                    CASE WHEN b.jarak_masuk > 100 THEN 1 ELSE 0 END + 
                    CASE WHEN b.jarak_pulang > 100 THEN 1 ELSE 0 END
                ) AS diluar_radius,
                SUM(CASE WHEN b.tanggal_pulang IS NOT NULL AND kategori != "cuti" THEN 1 ELSE 0 END) AS total_hari,
                SUM(b.menit_telat) AS total_menit_telat,
                AVG(b.menit_telat) AS rata_menit_telat,
                SUM(CASE WHEN b.tanggal_pulang IS NULL AND kategori != "cuti" THEN 1 ELSE 0 END) AS tidak_absen_pulang,
                0 AS total_menit_pulang_awal,
                FLOOR(COALESCE(SUM(b.menit_telat), 0) / 480) AS total_hari_telat
            ')->groupBy('a.id', 'a.uuid', 'a.name', 'a.jabatan', 'a.email' , 'a.wilayah', 'a.penempatan');

        if (isset($request->bulan)) {
            $datas = $datas->whereMonth('b.tanggal_masuk', $request->bulan);
        } 
        
        if (isset($request->uuid) && $request->fatch_json == false) {
            $datas = $datas->where('a.id', $user->id)->first();
            return response()->json($datas);
        } else if ($request->fatch_json) {
            $datas = $datas->groupBy('a.id')->get();
            $response = array(
                'status' => 200,
                'year' => $year,
                'data' => $datas
            );
            
            return response()->json($response, 200); 
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
            b.uuid, b.kode_shift_rules, b.tanggal_masuk, b.tanggal_pulang, 
            b.jam_masuk, b.jam_pulang, 
            b.jarak_masuk, b.jarak_pulang, 
            b.catatan_masuk, b.catatan_pulang, 
            b.menit_telat AS menit_terlambat,
            GROUP_CONCAT(DISTINCT d.alasan, '; ') AS alasan,
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
        ->whereNull('b.deleted_at')
        ->whereNull('d.deleted_at')
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
            'b.menit_telat',
            'b.uuid',
            'b.kode_shift_rules'
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

    public function load_perbaikan_per_pengajuan(Request $request){
        $datas = DB::table('perbaikan_absen as a')
            ->leftJoin('absen as b', 'a.absen_id', '=', 'b.id')
            ->leftJoin('users as c', 'c.id', '=', 'a.user_id')
            ->select(
                'a.uuid',
                'a.created_at',
                'b.tanggal_masuk',
                'c.name as nama',
                'a.tipe_absen',
                'a.tipe_perbaikan',
                'a.jam_sebelumnya',
                'a.jarak_sebelumnya',
                'a.alasan',
                'a.link_surat_tugas',
                'a.keterangan_pic',
                'a.disetujui'
            )
            ->whereNull('a.deleted_at');

        if (isset($request->tahun)) {
            $datas = $datas->whereYear('b.tanggal_masuk', $request->tahun);
        }

        if (isset($request->bulan)) {
            $datas = $datas->whereMonth('b.tanggal_masuk', $request->bulan);
        }

        $datas = $datas->orderBy('a.created_at', 'desc')
            ->orderBy('a.disetujui');
        
        $datas = $datas->get();
    
        return DataTables::of($datas)->make(true);
    }

    public function load_detail_user_perbulan(Request $request){
        return $this->load_perbaikan_per_pengajuan($request);
    }
}
