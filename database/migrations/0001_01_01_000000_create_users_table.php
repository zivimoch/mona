<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('uuid');
            $table->string('name');
            $table->string('email', 191)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->enum('jabatan', ['Super Admin', 'Penerima Pengaduan', 'Manajer Kasus', 'Pendamping Kasus', 'Psikolog', 'Konselor', 'Advokat', 'Paralegal', 'Unit Reaksi Cepat', 'Supervisor Kasus', 'Tenaga Ahli', 'Sekretariat', 'Kepala Instansi', 'Tim Data']);
            $table->string('password');
            $table->string('kantor_latitude')->nullable();
            $table->string('kantor_longitude')->nullable();
            $table->text('alamat')->nullable();
            $table->char('no_telp')->nullable();
            $table->integer('sisa_cuti')->default(12);
            $table->rememberToken();
            $table->timestamps();
            $table->dateTime('deleted_at')->nullable();
        });

        // Schema::create('password_reset_tokens', function (Blueprint $table) {
        //     $table->string('email')->primary();
        //     $table->string('token');
        //     $table->timestamp('created_at')->nullable();
        // });

        // Schema::create('sessions', function (Blueprint $table) {
        //     $table->string('id')->primary();
        //     $table->foreignId('user_id')->nullable()->index();
        //     $table->string('ip_address', 45)->nullable();
        //     $table->text('user_agent')->nullable();
        //     $table->longText('payload');
        //     $table->integer('last_activity')->index();
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
