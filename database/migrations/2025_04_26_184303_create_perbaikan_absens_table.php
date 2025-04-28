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
        Schema::create('perbaikan_absen', function (Blueprint $table) {
            $table->id();
            $table->string('uuid');
            $table->integer('absen_id');
            $table->integer('user_id');
            $table->enum('tipe_absen', ['masuk', 'pulang'])->default('masuk');
            $table->string('tipe_perbaikan');
            $table->time('jam_sebelumnya')->comment('jika kosong berarti tipe perbaikan bukan jam')->nullable();
            $table->string('jarak_sebelumnya')->comment('jika kosong berarti tipe perbaikan bukan jarak')->nullable();
            $table->string('alasan');
            $table->boolean('disetujui')->nullable();
            $table->string('keterangan_pic')->nullable();
            $table->timestamps();
            $table->dateTime('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perbaikan_absen');
    }
};
