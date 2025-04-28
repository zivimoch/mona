<?php

namespace App\Http\Controllers;

use App\Helpers\LogActivityHelper;
use App\Models\Absen;
use App\Models\Cuti;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;
use Exception;
use Validator;

class CutiController extends Controller
{

    function index() {
        return view('cuti.index');
    }

    function detail_user(Request $request) {
        $user = User::where('uuid', $request->user_id)->first();
        $akses = $user->id == Auth::user()->id;
        return view('cuti.cuti_user')
                ->with('akses', $akses)
                ->with('sisa_cuti', $user->sisa_cuti)
                ;
    }

    function load_detail(Request $request) {
        $user = User::where('uuid', $request->user_id)->first();

        $datas = DB::table('cuti as a')
            ->leftJoin('users as b', 'a.user_id', 'b.id')
            ->whereYear('a.created_at', $request->tahun)
            ->selectRaw('a.uuid,
                        b.name, 
                        b.jabatan, 
                        a.tanggal_cuti, 
                        a.jumlah_hari as hari_diajukan,
                        a.sisa_hari_sebelumnya,
                        a.alasan, 
                        a.alamat_selama_cuti, 
                        a.created_at, 
                        a.disetujui');

        if (Auth::user()->jabatan != 'Sekretariat') {
            $datas = $datas->where('a.user_id', Auth::user()->id);
        }
        $datas = $datas->whereNULL('a.deleted_at')->orderBy('a.created_at', 'DESC');
        // Fetch the results first
        $datas = $datas->get()->map(function ($item) {
            $item->tanggal_cuti = collect(explode(',', $item->tanggal_cuti))
                ->map(fn($date) => \Carbon\Carbon::parse(trim($date))->translatedFormat('d M Y'))
                ->implode(', ');
            return $item;
        });

        return DataTables::of($datas)->make(true);

    }

    function load_detail_pengajuan(Request $request) {
        $data = DB::table('cuti as a')
                    ->selectRaw('a.*')
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

                $user = User::where('id', Auth::user()->id)->first();         

                $jumlah_hari = count(explode(',', $request->tanggal_cuti));

                $data = [
                    'user_id' => Auth::user()->id,
                    'tanggal_cuti' => $request->tanggal_cuti,
                    'jumlah_hari' => $jumlah_hari,
                    'sisa_hari_sebelumnya' => $user->sisa_cuti,
                    'alamat_domisili' => $request->alamat_domisili,
                    'no_telp' => $request->no_telp,
                    'jabatan' => Auth::user()->jabatan,
                    'alamat_selama_cuti' => $request->alamat_selama_cuti,
                    'alasan' => $request->alasan,
                ];
                
            $proses = Cuti::updateOrCreate(['uuid' => $request->uuid], $data);
            
            LogActivityHelper::push_log(
                //message
                Auth::user()->name.' mengajukan cuti ',
            );
            // update identitas user
            User::where('id', $user->id)->update([
                'alamat' => $request->alamat_domisili,
                'no_telp' => $request->no_telp,
            ]);
                
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

    function persetujuan(Request $request) {
        try {
            $validator = Validator::make($request->all(), [
                ]);
                if ($validator->fails())
                {
                    throw new Exception($validator->errors());
                }
            $cuti = Cuti::where('uuid', $request->uuid)
                    ->selectRaw('*, jumlah_hari as hari_diajukan')
                    ->first();

            if ($request->disetujui) {
                // buat absen dengan kategori cuti
                $tanggalCutiArray = explode(',', $cuti->tanggal_cuti);

                foreach ($tanggalCutiArray as $value) {
                    Absen::create([
                        'user_id' => $cuti->user_id,
                        'kategori' => 'cuti',
                        'catatan_id' => $cuti->id,
                        'tanggal_masuk' => trim($value) // Remove any extra spaces
                    ]);
                }

                $user = User::where('id', $cuti->user_id)->first();
                User::where('id', $user->id)->update([
                    'sisa_cuti' => $user->sisa_cuti - $cuti->hari_diajukan
                ]);
            }
            
            $proses = Cuti::where('uuid', $request->uuid)->update([
                'disetujui' => $request->disetujui
            ]);
                
            //return response
            return response()->json([
                'success' => true,
                'code'    => 200,
                'message' => 'Data Berhasil Disimpan!',
            ]);
        } catch (Exception $e){
            return response()->json(['message' => $e->getMessage()]);
            die();
        }
    }

    // API
    function get_user_cuti(Request $request) {
        $token = $request->header('Authorization');
        if ($token !== 'Bearer ' . config('app.api_secret')) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $data = DB::table('absen as a')
                    ->leftJoin('users as b', 'a.user_id', 'b.id')
                    ->whereYear('a.tanggal_masuk', $request->tahun)
                    ->whereMonth('a.tanggal_masuk', $request->bulan)
                    ->where('a.kategori', 'cuti')
                    ->selectRaw('b.uuid, b.name, b.email, b.jabatan, count(a.id) as jumlah_cuti')
                    ->groupBy('b.id', 'b.uuid', 'b.name', 'b.email', 'b.jabatan');

        // $data = DB::table('users as a')
        //             ->leftJoin('absen as b', 'a.id', 'b.user_id')
        //             ->whereYear('b.tanggal_masuk', $request->tahun)
        //             ->whereMonth('b.tanggal_masuk', $request->bulan)
        //             ->where('b.kategori', 'cuti')
        //             ->selectRaw('a.uuid, a.name, a.email, a.jabatan, count(b.id) as jumlah_cuti')
        //             ->groupBy('a.id', 'a.uuid', 'a.name', 'a.email', 'a.jabatan');
        if ($request->email) {
            $data = $data->where('b.email', $request->email)->first();
        } else {
            $data = $data->get();
        }
        // Return UUID & Address
        return response()->json($data);
    }

    function permohonan(Request $request){
        $data = DB::table('cuti as a')
                    ->leftJoin('users as b', 'a.user_id', 'b.id')
                    ->selectRaw('a.*, b.name, b.jabatan')
                    ->where('a.uuid', $request->uuid)
                    ->first();
        // jika sudah disetujui pemohon dan atasan maka otomatis disetujui kasubagTU dan kepala
        if ($data->tandatangan1 && $data->tandatangan2) {
            $datas['tandatangan3'] = 'buyuni.png';
            $datas['nama_penandatangan3'] = 'Yuni';
            $datas['tandatangan4'] = 'buyuni.png';
            $datas['nama_penandatangan4'] = 'Yuni';
            Cuti::updateOrCreate(['uuid' => $data->uuid], $datas);
        }

        return view('cuti.permohonan')
                ->with('data', $data);
    }   

    function store_permohonan(Request $request) {
        try {
            $validator = Validator::make($request->all(), [
                ]);
                if ($validator->fails())
                {
                    throw new Exception($validator->errors());
                }
                $data = [];

                if (isset($request->tandatangan1)) {
                    //simpan tandatangan 1 atasan
                    $folderPath1 = public_path('img/tandatangan/ttd_cuti/');
                    $image_parts1 = explode(";base64,", $request->tandatangan1);
                    $image_type_aux1 = explode("image/", $image_parts1[0]);
                    $image_type1 = $image_type_aux1[1];
                    $image_base641 = base64_decode($image_parts1[1]);
                    $file1 = uniqid() . '.'.$image_type1;
                    $filepath1 = $folderPath1 . $file1;
                    file_put_contents($filepath1, $image_base641);

                    $data['tandatangan1'] = $file1;
                    $data['nama_penandatangan1'] = $request->nama_penandatangan1;
                }
                
                if (isset($request->tandatangan2)) {
                    //simpan tandatangan 2 pegawai
                    $folderPath2 = public_path('img/tandatangan/ttd_cuti/');
                    $image_parts2 = explode(";base64,", $request->tandatangan2);
                    $image_type_aux2 = explode("image/", $image_parts2[0]);
                    $image_type2 = $image_type_aux2[1];
                    $image_base642 = base64_decode($image_parts2[1]);
                    $file2 = uniqid() . '.'.$image_type2;
                    $filepath2 = $folderPath2 . $file2;
                    file_put_contents($filepath2, $image_base642);

                    $data['tandatangan2'] = $file2;
                    $data['nama_penandatangan2'] = $request->nama_penandatangan2;
                }
                
                if (isset($request->tandatangan3)) {
                    //simpan tandatangan 3 kasubbag
                    $folderPath3 = public_path('img/tandatangan/ttd_cuti/');
                    $image_parts3 = explode(";base64,", $request->tandatangan3);
                    $image_type_aux3 = explode("image/", $image_parts3[0]);
                    $image_type3 = $image_type_aux3[1];
                    $image_base643 = base64_decode($image_parts3[1]);
                    $file3 = uniqid() . '.'.$image_type3;
                    $filepath3 = $folderPath3 . $file3;
                    file_put_contents($filepath3, $image_base643);

                    $data['tandatangan3'] = $file3;
                    $data['nama_penandatangan3'] = $request->nama_penandatangan3;
                }
                
                if (isset($request->tandatangan4)) {
                    //simpan tandatangan 4 kepala
                    $folderPath4 = public_path('img/tandatangan/ttd_cuti/');
                    $image_parts4 = explode(";base64,", $request->tandatangan4);
                    $image_type_aux4 = explode("image/", $image_parts4[0]);
                    $image_type4 = $image_type_aux4[1];
                    $image_base644 = base64_decode($image_parts4[1]);
                    $file4 = uniqid() . '.'.$image_type4;
                    $filepath4 = $folderPath4 . $file4;
                    file_put_contents($filepath4, $image_base644);

                    $data['tandatangan4'] = $file4;
                    $data['nama_penandatangan4'] = $request->nama_penandatangan4;
                }
                
            $proses = Cuti::updateOrCreate(['uuid' => $request->uuid], $data);
            return back()->with('success', 'Data Berhasil Disimpan!');
        } catch (Exception $e){
            return response()->json(['message' => $e->getMessage()]);
            die();
        }
        
    }

    function destroy(Request $request)
    {
        try {
            $cuti = Cuti::where('uuid', $request->uuid)->first();
            if ($cuti == null) {
                throw new Exception('Data tidak ditemukan!');
            }

            if ($cuti->disetujui) {
                // jika cuti sudah disetujui, tambahkan sisa cuti user
                $user = User::where('id', $cuti->user_id)->first();
                User::where('id', $cuti->user_id)->update([
                    'sisa_cuti' => $user->sisa_cuti + $cuti->jumlah_hari
                ]);

                // hapus absen dengan kategori cuti
                Absen::where('catatan_id', $cuti->id)->delete();
            }

            $cuti->delete();

            return response()->json([
                'success' => true,
                'code'    => 200,
                'message' => 'Data Berhasil Dihapus!'
            ]);
        } catch (Exception $e){
            return response()->json(['message' => $e->getMessage()]);
            die();
        }
        
    }
}
