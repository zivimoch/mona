<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AbsenController extends Controller
{
    public function load_tanggal(Request $request) {
        // Get the requested month and year, default to current month and year if not provided
        $month = $request->input('month', \Carbon\Carbon::now()->month);  // Default to current month if not provided
        $year = $request->input('year', \Carbon\Carbon::now()->year);    // Default to current year if not provided
    
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
    
        // Build the query with the list of dates
        $query = "SELECT 
                    a.date_tanggal_bulan_ini AS tanggal,
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
                ORDER BY a.date_tanggal_bulan_ini DESC, b.jam_masuk DESC
            ";
    
        // Execute the query
        $results = DB::select($query);
        
        return response()->json($results);
    }
            
    

    // panggil detail absen per tanggal
    public function load_detail() {
        
    }

    // panggil detail agenda per tanggal
    public function load_agenda() {
        
    }
}
