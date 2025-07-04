<?php

namespace Database\Seeders;

use App\Models\Absen;
use App\Models\ShiftRules;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //data user
        $csvFile = fopen(base_path("database/data/users.csv"), "r");
        $firstline = true;
        while (($users = fgetcsv($csvFile, 2000, ",")) !== FALSE) {
            if (!$firstline) {
                User::create([
                    "id" => $users['0'],
                    "uuid" => $users['1'],
                    "name" => $users['2'],
                    "email" => $users['3'],
                    "email_verified_at" => Carbon::now(),
                    "jabatan" => $users['5'],
                    "password" => $users['6'],
                    "kantor_latitude" => $users['7'],
                    "kantor_longitude" => $users['8'],
                    "alamat" => $users['9'],
                    "no_telp" => $users['10'],
                    "sisa_cuti" => $users['11'],
                    "remember_token" => NULL,
                    "created_at" => Carbon::now(),
                    "updated_at" => Carbon::now(),
                    "deleted_at" => NULL,
                ]);    
            }
            $firstline = false;
        }
        fclose($csvFile);

        // User::factory()->create([
        //     'name' => 'Akun Demo',
        //     'email' => 'demo@mona.ol',
        //     'password' => '$2y$10$9BcBcEWaVUmAOrl2zZkaKeYRZaajkbtNFlAcpNTwkXDab9e00kqlq',
        //     'kantor_latitude' => '-6.190294962218336',
        //     'kantor_longitude' => '106.9053795256499',
        // ]);

         $shift_rules = [
            [
                'uuid' => 'd973864f-3ffe-44da-90c4-a96a9b612d60',
                'kode' => 1,
                'judul' => 'Reguler (07:30 s/d 16:00)',
                'jam_masuk' => '07:30',
                'jam_pulang' => '16:00',
            ],
            [
                'uuid' => 'd973864a-3ffe-44da-90c4-a96a9b612d60',
                'kode' => 2,
                'judul' => 'Reguler Jumat (07:30 s/d 16:30)',
                'jam_masuk' => '07:30',
                'jam_pulang' => '16:30',
            ],
            [
                'uuid' => 'd973824f-3ffe-44da-90c4-a96a9b612d60',
                'kode' => 3,
                'judul' => 'Shift 1 URC (08:00 s/d 16:00)',
                'jam_masuk' => '08:00',
                'jam_pulang' => '16:00',
            ],
            [
                'uuid' => 'd973464f-3ffe-44da-90c4-a96a9b612d60',
                'kode' => 4,
                'judul' => 'Shift 2 URC (16:00 s/d 00:00)',
                'jam_masuk' => '16:00',
                'jam_pulang' => '00:00',
            ],
            [
                'uuid' => 'd973p64f-3ffe-44da-90c4-a96a9b612d60',
                'kode' => 5,
                'judul' => 'Shift 3 URC (00:00 s/d 08:00)',
                'jam_masuk' => '00:00',
                'jam_pulang' => '08:00',
            ],
            [
                'uuid' => 'd97q864f-3ffe-44da-90c4-a96a9b612d60',
                'kode' => 6,
                'judul' => 'RPS (07:30 s/d 07:30)',
                'jam_masuk' => '07:30',
                'jam_pulang' => '07:30',
            ]
            ];
            ShiftRules::insert($shift_rules);

            $shift_rules = [
            [
                'uuid' => 'd973864f-3ffe-44da-90c4-a96a9b612d60',
                'kode' => 1,
                'judul' => 'Reguler (07:30 s/d 16:00)',
                'jam_masuk' => '07:30',
                'jam_pulang' => '16:00',
            ],
            [
                'uuid' => 'd973864a-3ffe-44da-90c4-a96a9b612d60',
                'kode' => 2,
                'judul' => 'Reguler Jumat (07:30 s/d 16:30)',
                'jam_masuk' => '07:30',
                'jam_pulang' => '16:30',
            ],
            [
                'uuid' => 'd973824f-3ffe-44da-90c4-a96a9b612d60',
                'kode' => 3,
                'judul' => 'Shift 1 URC (08:00 s/d 16:00)',
                'jam_masuk' => '08:00',
                'jam_pulang' => '16:00',
            ],
            [
                'uuid' => 'd973464f-3ffe-44da-90c4-a96a9b612d60',
                'kode' => 4,
                'judul' => 'Shift 2 URC (16:00 s/d 00:00)',
                'jam_masuk' => '16:00',
                'jam_pulang' => '00:00',
            ],
            [
                'uuid' => 'd973p64f-3ffe-44da-90c4-a96a9b612d60',
                'kode' => 5,
                'judul' => 'Shift 3 URC (00:00 s/d 08:00)',
                'jam_masuk' => '00:00',
                'jam_pulang' => '08:00',
            ],
            [
                'uuid' => 'd97q864f-3ffe-44da-90c4-a96a9b612d60',
                'kode' => 6,
                'judul' => 'RPS (07:30 s/d 07:30)',
                'jam_masuk' => '07:30',
                'jam_pulang' => '07:30',
            ]
            ];
            ShiftRules::insert($shift_rules);
        
        // $absen = [
        //     [
        //     'uuid' => '98283dy8723y8dy28d2h',
        //     'user_id' => '1',
        //     'kantor_latitude' => '-6.190294962218336',
        //     'kantor_longitude' => '106.9053795256499',
        //     'tanggal_masuk' => '2024-12-02',
        //     'jam_masuk' => '07:38:10',
        //     'masuk_latitude' => '-6.190294962218336',
        //     'masuk_longitude' => '106.9053795256499',
        //     'foto_masuk' => 'default.png',
        //     'catatan_masuk' => NULL,
        //     'tanggal_pulang' => '2024-12-02',
        //     'jam_pulang' => '16:30:00',
        //     'pulang_latitude' => '-6.190294962218336',
        //     'pulang_longitude' => '106.9053795256499',
        //     'foto_pulang' => 'default.png',
        //     // 'catatan_pulang ' => NULL,
        //     ],
        //     [
        //     'uuid' => '98283dy8723y8dy28d2h',
        //     'user_id' => '1',
        //     'kantor_latitude' => '-6.190294962218336',
        //     'kantor_longitude' => '106.9053795256499',
        //     'tanggal_masuk' => '2024-12-03',
        //     'jam_masuk' => '07:38:10',
        //     'masuk_latitude' => '-6.190294962218336',
        //     'masuk_longitude' => '106.9053795256499',
        //     'foto_masuk' => 'default.png',
        //     'catatan_masuk' => NULL,
        //     'tanggal_pulang' => '2024-12-03',
        //     'jam_pulang' => '16:30:00',
        //     'pulang_latitude' => '-6.190294962218336',
        //     'pulang_longitude' => '106.9053795256499',
        //     'foto_pulang' => 'default.png',
        //     // 'catatan_pulang ' => NULL,
        //     ],
        //     [
        //     'uuid' => '98283dy8723y8dy28d2h',
        //     'user_id' => '1',
        //     'kantor_latitude' => '-6.190294962218336',
        //     'kantor_longitude' => '106.9053795256499',
        //     'tanggal_masuk' => '2024-12-04',
        //     'jam_masuk' => '07:38:10',
        //     'masuk_latitude' => '-6.190294962218336',
        //     'masuk_longitude' => '106.9053795256499',
        //     'foto_masuk' => 'default.png',
        //     'catatan_masuk' => NULL,
        //     'tanggal_pulang' => '2024-12-04',
        //     'jam_pulang' => '16:30:00',
        //     'pulang_latitude' => '-6.190294962218336',
        //     'pulang_longitude' => '106.9053795256499',
        //     'foto_pulang' => 'default.png',
        //     // 'catatan_pulang ' => NULL,
        //     ],
        //     [
        //     'uuid' => '98283dy8723y8dy28d2h',
        //     'user_id' => '1',
        //     'kantor_latitude' => '-6.190294962218336',
        //     'kantor_longitude' => '106.9053795256499',
        //     'tanggal_masuk' => '2024-12-05',
        //     'jam_masuk' => '07:38:10',
        //     'masuk_latitude' => '-6.190294962218336',
        //     'masuk_longitude' => '106.9053795256499',
        //     'foto_masuk' => 'default.png',
        //     'catatan_masuk' => NULL,
        //     'tanggal_pulang' => '2024-12-05',
        //     'jam_pulang' => '16:30:00',
        //     'pulang_latitude' => '-6.190294962218336',
        //     'pulang_longitude' => '106.9053795256499',
        //     'foto_pulang' => 'default.png',
        //     // 'catatan_pulang ' => NULL,
        //     ],
        //     [
        //     'uuid' => '98283dy8723y8dy28d2h',
        //     'user_id' => '1',
        //     'kantor_latitude' => '-6.190294962218336',
        //     'kantor_longitude' => '106.9053795256499',
        //     'tanggal_masuk' => '2024-12-06',
        //     'jam_masuk' => '07:38:10',
        //     'masuk_latitude' => '-6.190294962218336',
        //     'masuk_longitude' => '106.9053795256499',
        //     'foto_masuk' => 'default.png',
        //     'catatan_masuk' => NULL,
        //     'tanggal_pulang' => '2024-12-06',
        //     'jam_pulang' => '16:30:00',
        //     'pulang_latitude' => '-6.190294962218336',
        //     'pulang_longitude' => '106.9053795256499',
        //     'foto_pulang' => 'default.png',
        //     // 'catatan_pulang ' => NULL,
        //     ],
        //     [
        //     'uuid' => '98283dy8723y8dy28d2h',
        //     'user_id' => '1',
        //     'kantor_latitude' => '-6.190294962218336',
        //     'kantor_longitude' => '106.9053795256499',
        //     'tanggal_masuk' => '2024-12-09',
        //     'jam_masuk' => '07:38:10',
        //     'masuk_latitude' => '-6.190294962218336',
        //     'masuk_longitude' => '106.9053795256499',
        //     'foto_masuk' => 'default.png',
        //     'catatan_masuk' => NULL,
        //     'tanggal_pulang' => '2024-12-09',
        //     'jam_pulang' => '16:30:00',
        //     'pulang_latitude' => '-6.190294962218336',
        //     'pulang_longitude' => '106.9053795256499',
        //     'foto_pulang' => 'default.png',
        //     // 'catatan_pulang ' => NULL,
        //     ],
        //     [
        //     'uuid' => '98283dy8723y8dy28d2h',
        //     'user_id' => '1',
        //     'kantor_latitude' => '-6.190294962218336',
        //     'kantor_longitude' => '106.9053795256499',
        //     'tanggal_masuk' => '2024-12-10',
        //     'jam_masuk' => '07:38:10',
        //     'masuk_latitude' => '-6.190294962218336',
        //     'masuk_longitude' => '106.9053795256499',
        //     'foto_masuk' => 'default.png',
        //     'catatan_masuk' => NULL,
        //     'tanggal_pulang' => '2024-12-10',
        //     'jam_pulang' => '16:30:00',
        //     'pulang_latitude' => '-6.190294962218336',
        //     'pulang_longitude' => '106.9053795256499',
        //     'foto_pulang' => 'default.png',
        //     // 'catatan_pulang ' => NULL,
        //     ],
        //     [
        //     'uuid' => '98283dy8723y8dy28d2h',
        //     'user_id' => '1',
        //     'kantor_latitude' => '-6.190294962218336',
        //     'kantor_longitude' => '106.9053795256499',
        //     'tanggal_masuk' => '2024-12-11',
        //     'jam_masuk' => '07:38:10',
        //     'masuk_latitude' => '-6.190294962218336',
        //     'masuk_longitude' => '106.9053795256499',
        //     'foto_masuk' => 'default.png',
        //     'catatan_masuk' => NULL,
        //     'tanggal_pulang' => '2024-12-11',
        //     'jam_pulang' => '16:30:00',
        //     'pulang_latitude' => '-6.190294962218336',
        //     'pulang_longitude' => '106.9053795256499',
        //     'foto_pulang' => 'default.png',
        //     // 'catatan_pulang ' => NULL,
        //     ],
        //     [
        //     'uuid' => '98283dy8723y8dy28d2h',
        //     'user_id' => '1',
        //     'kantor_latitude' => '-6.190294962218336',
        //     'kantor_longitude' => '106.9053795256499',
        //     'tanggal_masuk' => '2024-12-12',
        //     'jam_masuk' => '07:38:10',
        //     'masuk_latitude' => '-6.190294962218336',
        //     'masuk_longitude' => '106.9053795256499',
        //     'foto_masuk' => 'default.png',
        //     'catatan_masuk' => NULL,
        //     'tanggal_pulang' => '2024-12-12',
        //     'jam_pulang' => '16:30:00',
        //     'pulang_latitude' => '-6.190294962218336',
        //     'pulang_longitude' => '106.9053795256499',
        //     'foto_pulang' => 'default.png',
        //     // 'catatan_pulang ' => NULL,
        //     ],
        //     [
        //     'uuid' => '98283dy8723y8dy28d2h',
        //     'user_id' => '1',
        //     'kantor_latitude' => '-6.190294962218336',
        //     'kantor_longitude' => '106.9053795256499',
        //     'tanggal_masuk' => '2024-12-13',
        //     'jam_masuk' => '07:38:10',
        //     'masuk_latitude' => '-6.190294962218336',
        //     'masuk_longitude' => '106.9053795256499',
        //     'foto_masuk' => 'default.png',
        //     'catatan_masuk' => NULL,
        //     'tanggal_pulang' => '2024-12-13',
        //     'jam_pulang' => '16:30:00',
        //     'pulang_latitude' => '-6.190294962218336',
        //     'pulang_longitude' => '106.9053795256499',
        //     'foto_pulang' => 'default.png',
        //     // 'catatan_pulang ' => NULL,
        //     ],
        //     [
        //     'uuid' => '98283dy8723y8dy28d2h',
        //     'user_id' => '1',
        //     'kantor_latitude' => '-6.190294962218336',
        //     'kantor_longitude' => '106.9053795256499',
        //     'tanggal_masuk' => '2024-12-16',
        //     'jam_masuk' => '07:38:10',
        //     'masuk_latitude' => '-6.190294962218336',
        //     'masuk_longitude' => '106.9053795256499',
        //     'foto_masuk' => 'default.png',
        //     'catatan_masuk' => NULL,
        //     'tanggal_pulang' => '2024-12-16',
        //     'jam_pulang' => '16:30:00',
        //     'pulang_latitude' => '-6.190294962218336',
        //     'pulang_longitude' => '106.9053795256499',
        //     'foto_pulang' => 'default.png',
        //     // 'catatan_pulang ' => NULL,
        //     ],
        //     [
        //     'uuid' => '98283dy8723y8dy28d2h',
        //     'user_id' => '1',
        //     'kantor_latitude' => '-6.190294962218336',
        //     'kantor_longitude' => '106.9053795256499',
        //     'tanggal_masuk' => '2024-12-17',
        //     'jam_masuk' => '07:38:10',
        //     'masuk_latitude' => '-6.190294962218336',
        //     'masuk_longitude' => '106.9053795256499',
        //     'foto_masuk' => 'default.png',
        //     'catatan_masuk' => NULL,
        //     'tanggal_pulang' => '2024-12-17',
        //     'jam_pulang' => '16:30:00',
        //     'pulang_latitude' => '-6.190294962218336',
        //     'pulang_longitude' => '106.9053795256499',
        //     'foto_pulang' => 'default.png',
        //     // 'catatan_pulang ' => NULL,
        //     ],
        //     [
        //     'uuid' => '98283dy8723y8dy28d2h',
        //     'user_id' => '1',
        //     'kantor_latitude' => '-6.190294962218336',
        //     'kantor_longitude' => '106.9053795256499',
        //     'tanggal_masuk' => '2024-12-18',
        //     'jam_masuk' => '07:38:10',
        //     'masuk_latitude' => '-6.190294962218336',
        //     'masuk_longitude' => '106.9053795256499',
        //     'foto_masuk' => 'default.png',
        //     'catatan_masuk' => NULL,
        //     'tanggal_pulang' => '2024-12-18',
        //     'jam_pulang' => '16:30:00',
        //     'pulang_latitude' => '-6.190294962218336',
        //     'pulang_longitude' => '106.9053795256499',
        //     'foto_pulang' => 'default.png',
        //     // 'catatan_pulang ' => NULL,
        //     ],
        //     [
        //     'uuid' => '98283dy8723y8dy28d2h',
        //     'user_id' => '1',
        //     'kantor_latitude' => '-6.190294962218336',
        //     'kantor_longitude' => '106.9053795256499',
        //     'tanggal_masuk' => '2024-12-19',
        //     'jam_masuk' => '07:38:10',
        //     'masuk_latitude' => '-6.190294962218336',
        //     'masuk_longitude' => '106.9053795256499',
        //     'foto_masuk' => 'default.png',
        //     'catatan_masuk' => NULL,
        //     'tanggal_pulang' => '2024-12-19',
        //     'jam_pulang' => '16:30:00',
        //     'pulang_latitude' => '-6.190294962218336',
        //     'pulang_longitude' => '106.9053795256499',
        //     'foto_pulang' => 'default.png',
        //     // 'catatan_pulang ' => NULL,
        //     ],
        //     [
        //     'uuid' => '98283dy8723y8dy28d2h',
        //     'user_id' => '1',
        //     'kantor_latitude' => '-6.190294962218336',
        //     'kantor_longitude' => '106.9053795256499',
        //     'tanggal_masuk' => '2024-12-20',
        //     'jam_masuk' => '07:38:10',
        //     'masuk_latitude' => '-6.190294962218336',
        //     'masuk_longitude' => '106.9053795256499',
        //     'foto_masuk' => 'default.png',
        //     'catatan_masuk' => NULL,
        //     'tanggal_pulang' => '2024-12-20',
        //     'jam_pulang' => '16:30:00',
        //     'pulang_latitude' => '-6.190294962218336',
        //     'pulang_longitude' => '106.9053795256499',
        //     'foto_pulang' => 'default.png',
        //     // 'catatan_pulang ' => NULL,
        //     ],
        //     [
        //     'uuid' => '98283dy8723y8dy28d2h',
        //     'user_id' => '1',
        //     'kantor_latitude' => '-6.190294962218336',
        //     'kantor_longitude' => '106.9053795256499',
        //     'tanggal_masuk' => '2024-12-23',
        //     'jam_masuk' => '07:38:10',
        //     'masuk_latitude' => '-6.190294962218336',
        //     'masuk_longitude' => '106.9053795256499',
        //     'foto_masuk' => 'default.png',
        //     'catatan_masuk' => NULL,
        //     'tanggal_pulang' => '2024-12-23',
        //     'jam_pulang' => '16:30:00',
        //     'pulang_latitude' => '-6.190294962218336',
        //     'pulang_longitude' => '106.9053795256499',
        //     'foto_pulang' => 'default.png',
        //     // 'catatan_pulang ' => NULL,
        //     ],
        //     [
        //     'uuid' => '98283dy8723y8dy28d2h',
        //     'user_id' => '1',
        //     'kantor_latitude' => '-6.190294962218336',
        //     'kantor_longitude' => '106.9053795256499',
        //     'tanggal_masuk' => '2024-12-24',
        //     'jam_masuk' => '07:38:10',
        //     'masuk_latitude' => '-6.190294962218336',
        //     'masuk_longitude' => '106.9053795256499',
        //     'foto_masuk' => 'default.png',
        //     'catatan_masuk' => NULL,
        //     'tanggal_pulang' => '2024-12-24',
        //     'jam_pulang' => '16:30:00',
        //     'pulang_latitude' => '-6.190294962218336',
        //     'pulang_longitude' => '106.9053795256499',
        //     'foto_pulang' => 'default.png',
        //     // 'catatan_pulang ' => NULL,
        //     ],
        //     [
        //     'uuid' => '98283dy8723y8dy28d2h',
        //     'user_id' => '1',
        //     'kantor_latitude' => '-6.190294962218336',
        //     'kantor_longitude' => '106.9053795256499',
        //     'tanggal_masuk' => '2024-12-25',
        //     'jam_masuk' => '07:38:10',
        //     'masuk_latitude' => '-6.190294962218336',
        //     'masuk_longitude' => '106.9053795256499',
        //     'foto_masuk' => 'default.png',
        //     'catatan_masuk' => NULL,
        //     'tanggal_pulang' => '2024-12-25',
        //     'jam_pulang' => '16:30:00',
        //     'pulang_latitude' => '-6.190294962218336',
        //     'pulang_longitude' => '106.9053795256499',
        //     'foto_pulang' => 'default.png',
        //     // 'catatan_pulang ' => NULL,
        //     ],
        //     [
        //     'uuid' => '98283dy8723y8dy28d2h',
        //     'user_id' => '1',
        //     'kantor_latitude' => '-6.190294962218336',
        //     'kantor_longitude' => '106.9053795256499',
        //     'tanggal_masuk' => '2024-12-27',
        //     'jam_masuk' => '07:38:10',
        //     'masuk_latitude' => '-6.190294962218336',
        //     'masuk_longitude' => '106.9053795256499',
        //     'foto_masuk' => 'default.png',
        //     'catatan_masuk' => NULL,
        //     'tanggal_pulang' => '2024-12-27',
        //     'jam_pulang' => '16:30:00',
        //     'pulang_latitude' => '-6.190294962218336',
        //     'pulang_longitude' => '106.9053795256499',
        //     'foto_pulang' => 'default.png',
        //     // 'catatan_pulang ' => NULL,
        //     ],
        //     [
        //     'uuid' => '98283dy8723y8dy28d2h',
        //     'user_id' => '1',
        //     'kantor_latitude' => '-6.190294962218336',
        //     'kantor_longitude' => '106.9053795256499',
        //     'tanggal_masuk' => '2024-12-30',
        //     'jam_masuk' => '07:38:10',
        //     'masuk_latitude' => '-6.190294962218336',
        //     'masuk_longitude' => '106.9053795256499',
        //     'foto_masuk' => 'default.png',
        //     'catatan_masuk' => NULL,
        //     'tanggal_pulang' => '2024-12-30',
        //     'jam_pulang' => '16:30:00',
        //     'pulang_latitude' => '-6.190294962218336',
        //     'pulang_longitude' => '106.9053795256499',
        //     'foto_pulang' => 'default.png',
        //     // 'catatan_pulang ' => NULL,
        //     ],
        //     [
        //     'uuid' => '98283dy8723y8dy28d2h',
        //     'user_id' => '1',
        //     'kantor_latitude' => '-6.190294962218336',
        //     'kantor_longitude' => '106.9053795256499',
        //     'tanggal_masuk' => '2024-12-31',
        //     'jam_masuk' => '07:38:10',
        //     'masuk_latitude' => '-6.190294962218336',
        //     'masuk_longitude' => '106.9053795256499',
        //     'foto_masuk' => 'default.png',
        //     'catatan_masuk' => NULL,
        //     'tanggal_pulang' => '2024-12-31',
        //     'jam_pulang' => '16:30:00',
        //     'pulang_latitude' => '-6.190294962218336',
        //     'pulang_longitude' => '106.9053795256499',
        //     'foto_pulang' => 'default.png',
        //     // 'catatan_pulang ' => NULL,
        //     ]
        // ];
        // Absen::insert($absen);
    }
}
