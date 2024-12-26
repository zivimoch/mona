<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AbsenController extends Controller
{
    public function load_tanggal() {
        // Raw SQL query with date formatting in MySQL
        $results = DB::select("
            WITH RECURSIVE dates AS (
                SELECT DATE_FORMAT(NOW(), '%Y-%m-01') AS date
                UNION ALL
                SELECT DATE_ADD(date, INTERVAL 1 DAY)
                FROM dates
                WHERE DATE_ADD(date, INTERVAL 1 DAY) <= NOW()
            )
            SELECT 
                a.date as tanggal, 
                b.tanggal_masuk, 
                b.jam_masuk, 
                b.foto_masuk, 
                b.tanggal_pulang, 
                b.jam_pulang, 
                b.foto_pulang,
                CONCAT( CASE DAYOFWEEK(a.date) WHEN 1 THEN 'Minggu' WHEN 2 THEN 'Senin' WHEN 3 THEN 'Selasa' WHEN 4 THEN 'Rabu' WHEN 5 THEN 'Kamis' WHEN 6 THEN 'Jumat' WHEN 7 THEN 'Sabtu' END, ', ', DAY(a.date), ' ', CASE MONTH(a.date) WHEN 1 THEN 'Jan' WHEN 2 THEN 'Feb' WHEN 3 THEN 'Mar' WHEN 4 THEN 'Apr' WHEN 5 THEN 'Mei' WHEN 6 THEN 'Jun' WHEN 7 THEN 'Jul' WHEN 8 THEN 'Ags' WHEN 9 THEN 'Sep' WHEN 10 THEN 'Okt' WHEN 11 THEN 'Nov' WHEN 12 THEN 'Des' END, ' ', YEAR(a.date) ) 
                AS tanggal_human,
                CONCAT( CASE DAYOFWEEK(b.tanggal_masuk) WHEN 1 THEN 'Minggu' WHEN 2 THEN 'Senin' WHEN 3 THEN 'Selasa' WHEN 4 THEN 'Rabu' WHEN 5 THEN 'Kamis' WHEN 6 THEN 'Jumat' WHEN 7 THEN 'Sabtu' END, ', ', DAY(b.tanggal_masuk), ' ', CASE MONTH(b.tanggal_masuk) WHEN 1 THEN 'Jan' WHEN 2 THEN 'Feb' WHEN 3 THEN 'Mar' WHEN 4 THEN 'Apr' WHEN 5 THEN 'Mei' WHEN 6 THEN 'Jun' WHEN 7 THEN 'Jul' WHEN 8 THEN 'Ags' WHEN 9 THEN 'Sep' WHEN 10 THEN 'Okt' WHEN 11 THEN 'Nov' WHEN 12 THEN 'Des' END, ' ', YEAR(b.tanggal_masuk) ) 
                AS tanggal_masuk_human,
                CONCAT( CASE DAYOFWEEK(b.tanggal_pulang) WHEN 1 THEN 'Minggu' WHEN 2 THEN 'Senin' WHEN 3 THEN 'Selasa' WHEN 4 THEN 'Rabu' WHEN 5 THEN 'Kamis' WHEN 6 THEN 'Jumat' WHEN 7 THEN 'Sabtu' END, ', ', DAY(b.tanggal_pulang), ' ', CASE MONTH(b.tanggal_pulang) WHEN 1 THEN 'Jan' WHEN 2 THEN 'Feb' WHEN 3 THEN 'Mar' WHEN 4 THEN 'Apr' WHEN 5 THEN 'Mei' WHEN 6 THEN 'Jun' WHEN 7 THEN 'Jul' WHEN 8 THEN 'Ags' WHEN 9 THEN 'Sep' WHEN 10 THEN 'Okt' WHEN 11 THEN 'Nov' WHEN 12 THEN 'Des' END, ' ', YEAR(b.tanggal_pulang) ) 
                AS tanggal_pulang_human
            FROM dates a 
            LEFT JOIN absen b 
            ON a.date = b.tanggal_masuk
            ORDER BY a.date DESC, b.jam_masuk DESC
        ");
    
        // Return the results as JSON
        return response()->json($results);
    }

    // panggil detail absen per tanggal
    public function load_detail() {
        
    }

    // panggil detail agenda per tanggal
    public function load_agenda() {
        
    }
}
