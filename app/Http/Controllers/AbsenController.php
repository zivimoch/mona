<?php

namespace App\Http\Controllers;

use App\Helpers\LogActivityHelper;
use App\Models\Absen;
use App\Models\PerbaikanAbsen;
use App\Models\ShiftRules;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\Http;
use Laravel\Ui\Presets\React;
use Validator;

class AbsenController extends Controller
{
    public function load_tanggal(Request $request) {
        // Get the requested month and year, default to current month and year if not provided
        $month = $request->input('bulan', \Carbon\Carbon::now()->month);  // Default to current month if not provided
        $year = $request->input('tahun', \Carbon\Carbon::now()->year);    // Default to current year if not provided
    
        // Get the start date (first day of the requested month and year)
        $currentMonthStart = \Carbon\Carbon::createFromDate($year, $month, 1)->startOfMonth()->format('Y-m-d');
        
        // If the requested month is the current month, set the end date to today's date
        if ($month == \Carbon\Carbon::now()->month && $year == \Carbon\Carbon::now()->year) {
            $currentMonthEnd = \Carbon\Carbon::today()->format('Y-m-d');  // Set to today's date
        } else {
            // Otherwise, set the end date to the last day of the requested month
            $currentMonthEnd = \Carbon\Carbon::createFromDate($year, $month, 1)->endOfMonth()->format('Y-m-d');
        }
    
        // Generate an array of all dates from the start date to the end date
        $dateRange = [];
        $startDate = new \DateTime($currentMonthStart);
        $endDate = new \DateTime($currentMonthEnd);
        
        // Loop through and create an array of dates
        for ($date = $startDate; $date <= $endDate; $date->modify('+1 day')) {
            $dateRange[] = $date->format('Y-m-d');
        }

        if ($request->tanggal_tidak_masuk == 'sembunyikan') {
            $tanggal_tidak_masuk = "WHERE b.tanggal_masuk IS NOT NULL OR a.date_tanggal_bulan_ini = CURRENT_DATE";
        } else {
            $tanggal_tidak_masuk = "";
        }
    
        // Build the query with the list of dates
        $query = "SELECT 
                    a.date_tanggal_bulan_ini AS tanggal,
                    b.uuid, 
                    b.kategori,
                    b.tanggal_masuk, 
                    b.jam_masuk, 
                    b.foto_masuk, 
                    b.menit_telat,
                    b.tanggal_pulang, 
                    b.jam_pulang, 
                    b.foto_pulang,
                    CONCAT(
                        CASE DAYOFWEEK(a.date_tanggal_bulan_ini) 
                            WHEN 1 THEN 'Minggu' 
                            WHEN 2 THEN 'Senin' 
                            WHEN 3 THEN 'Selasa' 
                            WHEN 4 THEN 'Rabu' 
                            WHEN 5 THEN 'Kamis' 
                            WHEN 6 THEN 'Jumat' 
                            WHEN 7 THEN 'Sabtu' 
                        END, 
                        ', ', 
                        DAY(a.date_tanggal_bulan_ini), 
                        ' ', 
                        CASE MONTH(a.date_tanggal_bulan_ini) 
                            WHEN 1 THEN 'Jan' 
                            WHEN 2 THEN 'Feb' 
                            WHEN 3 THEN 'Mar' 
                            WHEN 4 THEN 'Apr' 
                            WHEN 5 THEN 'Mei' 
                            WHEN 6 THEN 'Jun' 
                            WHEN 7 THEN 'Jul' 
                            WHEN 8 THEN 'Ags' 
                            WHEN 9 THEN 'Sep' 
                            WHEN 10 THEN 'Okt' 
                            WHEN 11 THEN 'Nov' 
                            WHEN 12 THEN 'Des' 
                        END, 
                        ' ', 
                        YEAR(a.date_tanggal_bulan_ini)
                    ) AS tanggal_human,
                    CONCAT(
                        CASE DAYOFWEEK(b.tanggal_masuk) 
                            WHEN 1 THEN 'Minggu' 
                            WHEN 2 THEN 'Senin' 
                            WHEN 3 THEN 'Selasa' 
                            WHEN 4 THEN 'Rabu' 
                            WHEN 5 THEN 'Kamis' 
                            WHEN 6 THEN 'Jumat' 
                            WHEN 7 THEN 'Sabtu' 
                        END, 
                        ', ', 
                        DAY(b.tanggal_masuk), 
                        ' ', 
                        CASE MONTH(b.tanggal_masuk) 
                            WHEN 1 THEN 'Jan' 
                            WHEN 2 THEN 'Feb' 
                            WHEN 3 THEN 'Mar' 
                            WHEN 4 THEN 'Apr' 
                            WHEN 5 THEN 'Mei' 
                            WHEN 6 THEN 'Jun' 
                            WHEN 7 THEN 'Jul' 
                            WHEN 8 THEN 'Ags' 
                            WHEN 9 THEN 'Sep' 
                            WHEN 10 THEN 'Okt' 
                            WHEN 11 THEN 'Nov' 
                            WHEN 12 THEN 'Des' 
                        END, 
                        ' ', 
                        YEAR(b.tanggal_masuk)
                    ) AS tanggal_masuk_human,
                    CONCAT(
                        CASE DAYOFWEEK(b.tanggal_pulang) 
                            WHEN 1 THEN 'Minggu' 
                            WHEN 2 THEN 'Senin' 
                            WHEN 3 THEN 'Selasa' 
                            WHEN 4 THEN 'Rabu' 
                            WHEN 5 THEN 'Kamis' 
                            WHEN 6 THEN 'Jumat' 
                            WHEN 7 THEN 'Sabtu' 
                        END, 
                        ', ', 
                        DAY(b.tanggal_pulang), 
                        ' ', 
                        CASE MONTH(b.tanggal_pulang) 
                            WHEN 1 THEN 'Jan' 
                            WHEN 2 THEN 'Feb' 
                            WHEN 3 THEN 'Mar' 
                            WHEN 4 THEN 'Apr' 
                            WHEN 5 THEN 'Mei' 
                            WHEN 6 THEN 'Jun' 
                            WHEN 7 THEN 'Jul' 
                            WHEN 8 THEN 'Ags' 
                            WHEN 9 THEN 'Sep' 
                            WHEN 10 THEN 'Okt' 
                            WHEN 11 THEN 'Nov' 
                            WHEN 12 THEN 'Des' 
                        END, 
                        ' ', 
                        YEAR(b.tanggal_pulang)
                    ) AS tanggal_pulang_human
                  FROM (
                    ";
    
        // Loop through the dateRange and build the query
        foreach ($dateRange as $date) {
            $query .= "SELECT '$date' AS date_tanggal_bulan_ini UNION ALL ";
        }
    
        // Remove the last UNION ALL
        $query = rtrim($query, " UNION ALL ");
        
        $query .= "
                ) a
                LEFT JOIN 
                ( SELECT * FROM absen WHERE user_id = ".Auth::user()->id." AND deleted_at IS NULL) 
                b ON a.date_tanggal_bulan_ini = b.tanggal_masuk
                ".$tanggal_tidak_masuk."
                ORDER BY a.date_tanggal_bulan_ini DESC, b.jam_masuk DESC
            ";
    
        // Execute the query
        $results = DB::select($query);
        
        return response()->json($results);
    }

    public function load_detail(Request $request) {
        $data = DB::table('absen as a')
                    ->leftJoin('shift_rules as b', 'a.kode_shift_rules', 'b.kode')
                    ->selectRaw('a.*, b.judul as rules, b.jam_masuk as jam_masuk_rules, b.jam_pulang as jam_pulang_rules')
                    ->where('a.uuid', $request->uuid)
                    ->first();

        if (!$data) {
            return null;
        }

        if ($data->jam_masuk > $data->jam_masuk_rules) {
            $menit_telat = (strtotime($data->jam_masuk) - strtotime($data->jam_masuk_rules)) / 60;
            $penalty = min($menit_telat, 60) * 60;
            $data->jam_pulang_fleksi = date('H:i:s', strtotime($data->jam_pulang_rules) + $penalty);
        } else {
            // if (Auth::user()->jabatan == 'Unit Reaksi Cepat' || Auth::user()->kantor_latitude == '-6.183773887087405') {
                $data->jam_pulang_fleksi = $data->jam_pulang_rules;
            // } else {
                // $menit_awal = (strtotime($data->jam_masuk_rules) - strtotime($data->jam_masuk)) / 60;
                // $adjustment = min($menit_awal, 60) * 60;
                // $data->jam_pulang_fleksi = date('H:i:s', strtotime($data->jam_pulang_rules) - $adjustment);
            // }
        }
        // data perbaikan 
        $perbaikan = PerbaikanAbsen::where('absen_id', $data->id)
                        ->where('tipe_absen', $request->tipe)
                        ->whereNull('deleted_at')
                        ->get();
        $data->perbaikan = $perbaikan;        
    
        return response()->json($data);
    }
    
            
   /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                ]);
                if ($validator->fails())
                {
                    throw new Exception($validator->errors());
                }

                $uuid = $request->uuid;

                $data = [
                    'user_id' => Auth::user()->id, 
                    'kantor_latitude' => Auth::user()->kantor_latitude, 
                    'kantor_longitude' => Auth::user()->kantor_longitude,
                    'tanggal_' . $request->tipe => now()->toDateString(), // Current date (YYYY-MM-DD)
                    'jam_' . $request->tipe => now()->toTimeString(),    // Current time (HH:MM:SS)
                    $request->tipe . '_latitude' => $request->my_latitude,
                    $request->tipe . '_longitude' => $request->my_longitude,
                    'foto_' . $request->tipe => $request->foto,
                    'catatan_' . $request->tipe => $request->catatan,
                    'jarak_' . $request->tipe => $request->jarak,
                ];

                if ($request->tipe == 'masuk') {
                    $data['kode_shift_rules'] = $request->shift;

                    $jamMasuk = ShiftRules::where('kode', $request->shift)->value('jam_masuk');
                    $jamMasukPlusOneHour = Carbon::parse($jamMasuk)->addHour();
                    if (now()->greaterThan($jamMasukPlusOneHour)) {
                        $menitTelat = (int) $jamMasukPlusOneHour->diffInMinutes(now());
                    } else {
                        $menitTelat = 0;
                    }
                    $data['menit_telat'] = $menitTelat; 


                    $absen = Absen::where('user_id', Auth::user()->id)
                                    ->where('tanggal_masuk', now()->toDateString())
                                    ->where('kode_shift_rules', $request->shift)
                                    ->where('deleted_at', null)
                                    ->first();
                    if ($absen) {
                        $uuid = $absen->uuid;
                    }
                }

                LogActivityHelper::push_log(
                    //message
                    Auth::user()->name.' melakukan absen '.$request->tipe,
                );
                
                $proses = Absen::updateOrCreate(['uuid' => $uuid], $data);

                // insert permohonan jika mengajukan 
                if ($request->catatan_absen) {
                    $data_perbaikan = [
                        'absen_id' => $proses->id, 
                        'user_id' => Auth::user()->id, 
                        'tipe_absen' => $request->tipe, 
                        'tipe_perbaikan' => json_encode($request->tipe_perbaikan_absen), 
                        'alasan' => $request->catatan_absen
                    ];

                    if (in_array('jam', $request->tipe_perbaikan_absen)) { 
                        $data_perbaikan['jam_sebelumnya'] = $proses->{'jam_' . $request->tipe};
                    }
                    if (in_array('jarak', $request->tipe_perbaikan_absen)) { 
                        $data_perbaikan['jarak_sebelumnya'] = $proses->{'jarak_' . $request->tipe};
                    }
                    $proses = PerbaikanAbsen::create($data_perbaikan);
                }
            //return response
            return response()->json([
                'success' => true,
                'code'    => 200,
                'message' => 'Data Berhasil Disimpan!'
            ]);
        } catch (Exception $e){
            return response()->json(['message' => $e->getMessage()]);
            die();
        }
    }

    function load_agenda(Request $request) {
        $data_agenda_moka = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('API_SECRET_KEY'),
            'Accept' => 'application/json'
        ])->get('https://mokapppa.jakarta.go.id/v2/agenda/showdate_api/'.$request->tanggal_masuk.'?email='.Auth::user()->email);
        
        if ($data_agenda_moka->failed()) {
            return response()->json(['message' => 'Request failed'], 500);
        }

        return response()->json($data_agenda_moka->json());
    }

    public function store_perbaikan(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                ]);
                if ($validator->fails())
                {
                    throw new Exception($validator->errors());
                }

                $absen = Absen::where('uuid', $request->uuid_absen)->first();

                $uuid = $request->uuid;

                if ($request->disetujui == null) {
                    // untuk submit user
                    $data = [
                        'absen_id' => $absen->id, 
                        'user_id' => Auth::user()->id, 
                        'tipe_absen' => $request->tipe_absen, 
                        'tipe_perbaikan' => json_encode($request->tipe_perbaikan), 
                        'alasan' => $request->catatan
                    ];

                    if (in_array('jam', $request->tipe_perbaikan)) { 
                        $data['jam_sebelumnya'] = $absen->{'jam_' . $request->tipe_absen};
                    }
                    if (in_array('jarak', $request->tipe_perbaikan)) { 
                        $data['jarak_sebelumnya'] = $absen->{'jarak_' . $request->tipe_absen};
                    }
                } else {
                    // untuk approval sekretariat
                    $data = [
                        'disetujui' => $request->disetujui,
                        'keterangan_pic' => $request->keterangan_pic,
                    ];

                    if ($request->disetujui == 1) {
                        // jika disetujui maka update Absen. Jika perubahan adalah jam maka jadikan jam masuk / pulang menjadi sesuai rule yang dipilih dan jadikan menit telat = 0.
                        // jika perubahan adalah jarak maka ubah distance jadi 0

                        $persetujuan_perbaikan = PerbaikanAbsen::where('uuid', $request->uuid)->first();
                        $absen_perbaikan = Absen::where('id', $persetujuan_perbaikan->absen_id)->first();
                        if ($persetujuan_perbaikan->jam_sebelumnya != null) {
                            $absen->menit_telat = 0;
                            $absen->{'jam_' . $request->tipe_absen} = ShiftRules::where('kode', $absen_perbaikan->kode_shift_rules)->value('jam_' . $request->tipe_absen);
                        }
                        if ($persetujuan_perbaikan->jarak_sebelumnya != null) {
                            $absen->{'jarak_' . $request->tipe_absen} = 0;
                        }
                        $absen->save();
                    }
                }
                
                $proses = PerbaikanAbsen::updateOrCreate(['uuid' => $uuid], $data);
                
            //return response
            return response()->json([
                'success' => true,
                'code'    => 200,
                'message' => 'Data Berhasil Disimpan!'
            ]);
        } catch (Exception $e){
            return response()->json(['message' => $e->getMessage()]);
            die();
        }
    }

    function load_perbaikan(Request $request) {
        $data = PerbaikanAbsen::where('uuid', $request->uuid)
                    ->whereNull('deleted_at')
                    ->first();
        $absen = Absen::where('id', $data->absen_id)->first();
        $data->uuid_absen = $absen->uuid;
        return response()->json($data);
    }

    public function perbaikan() {
        return view('rekap.perbaikan');
    }
}
