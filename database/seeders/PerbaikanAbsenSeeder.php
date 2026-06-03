<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PerbaikanAbsenSeeder extends Seeder
{
    /**
     * Seed contoh pengajuan perbaikan absen.
     */
    public function run(): void
    {
        $now = Carbon::now();
        $tanggalMasuk = $now->copy()->subDay()->toDateString();

        DB::table('users')->updateOrInsert(
            ['email' => 'demo.perbaikan.absen@mona.test'],
            [
                'uuid' => '7f116f4b-2577-4f9a-92fa-05b62b8b7a91',
                'name' => 'Demo Pengajuan Perbaikan',
                'email_verified_at' => $now,
                'jabatan' => 'Sekretariat',
                'wilayah' => 'Jakarta Timur',
                'penempatan' => 'pusat',
                'password' => Hash::make('password'),
                'kantor_latitude' => '-6.190294962218336',
                'kantor_longitude' => '106.9053795256499',
                'alamat' => 'Kantor Pusat',
                'no_telp' => '081234567890',
                'sisa_cuti' => 12,
                'remember_token' => null,
                'created_at' => $now,
                'updated_at' => $now,
                'deleted_at' => null,
            ]
        );

        $userId = DB::table('users')
            ->where('email', 'demo.perbaikan.absen@mona.test')
            ->value('id');

        DB::table('absen')->updateOrInsert(
            ['uuid' => '1dc4ba7d-11ea-4927-bc56-e10c75afab11'],
            [
                'user_id' => $userId,
                'kategori' => 'masuk',
                'catatan_id' => null,
                'kantor_latitude' => '-6.190294962218336',
                'kantor_longitude' => '106.9053795256499',
                'kode_shift_rules' => 1,
                'tanggal_masuk' => $tanggalMasuk,
                'jam_masuk' => '08:12:00',
                'menit_telat' => 42,
                'masuk_latitude' => '-6.190294962218336',
                'masuk_longitude' => '106.9053795256499',
                'foto_masuk' => 'default.png',
                'catatan_masuk' => null,
                'jarak_masuk' => '185',
                'tanggal_pulang' => $tanggalMasuk,
                'jam_pulang' => '16:05:00',
                'pulang_latitude' => '-6.190294962218336',
                'pulang_longitude' => '106.9053795256499',
                'foto_pulang' => 'default.png',
                'catatan_pulang' => null,
                'jarak_pulang' => '0',
                'created_at' => $now,
                'updated_at' => $now,
                'deleted_at' => null,
            ]
        );

        $absenId = DB::table('absen')
            ->where('uuid', '1dc4ba7d-11ea-4927-bc56-e10c75afab11')
            ->value('id');

        DB::table('perbaikan_absen')->updateOrInsert(
            ['uuid' => '4e758fa1-66d0-44fc-b238-10cdfe398b4f'],
            [
                'absen_id' => $absenId,
                'user_id' => $userId,
                'tipe_absen' => 'masuk',
                'tipe_perbaikan' => 'jam,jarak',
                'jam_sebelumnya' => '08:12:00',
                'jarak_sebelumnya' => '185',
                'alasan' => 'Lupa mengaktifkan lokasi akurat saat absen masuk.',
                'link_surat_tugas' => 'https://example.com/surat-tugas-demo',
                'disetujui' => null,
                'keterangan_pic' => null,
                'created_at' => $now,
                'updated_at' => $now,
                'deleted_at' => null,
            ]
        );
    }
}
