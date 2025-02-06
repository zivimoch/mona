<?php

namespace App\Http\Controllers;

use App\Models\Absen;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Exception;
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
                    b.tanggal_masuk, 
                    b.jam_masuk, 
                    b.foto_masuk, 
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
                ( SELECT * FROM absen WHERE user_id = ".Auth::user()->id." ) 
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
                    ->selectRaw('a.*, b.jam_masuk as jam_masuk_rules, b.jam_pulang as jam_pulang_rules')
                    ->where('a.uuid', $request->uuid)
                    ->first();

        if (!$data) {
            return null;
        }

        if ($data->jam_masuk > $data->jam_masuk_rules) {
            // Calculate the difference in minutes between actual jam_masuk and the rules
            $menit_telat = (strtotime($data->jam_masuk) - strtotime($data->jam_masuk_rules)) / 60;
    
            if ($menit_telat > 60) {
                // Max penalty: extend jam_pulang by 60 minutes
                $jam_pulang_fleksi = date('H:i:s', strtotime($data->jam_pulang_rules) + 3600);
            } else {
                // Add lateness penalty to jam_pulang
                $jam_pulang_fleksi = date('H:i:s', strtotime($data->jam_pulang_rules) + ($menit_telat * 60));
            }
    
            // Append calculated fields to the data
            $data->jam_pulang_fleksi = $jam_pulang_fleksi;
        } else {
            // No lateness
            $data->jam_pulang_fleksi = $data->jam_pulang_rules;
        }
    
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
                }
                
                $proses = Absen::updateOrCreate(['uuid' => $request->uuid], $data);
                
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
}
