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
        Schema::create('cuti', function (Blueprint $table) {
            $table->id();
            $table->string('uuid');
            $table->integer('user_id');
            $table->string('tanggal_cuti');
            $table->integer('jumlah_hari');
            $table->integer('sisa_hari_sebelumnya');
            $table->text('alamat_domisili');
            $table->char('no_telp');
            $table->string('jabatan');
            $table->text('alamat_selama_cuti');
            $table->text('alasan');
            $table->string('tandatangan1')->nullable();
            $table->string('nama_penandatangan1')->nullable();
            $table->string('tandatangan2')->nullable();
            $table->string('nama_penandatangan2')->nullable();
            $table->string('tandatangan3')->nullable();
            $table->string('nama_penandatangan3')->nullable();
            $table->string('tandatangan4')->nullable();
            $table->string('nama_penandatangan4')->nullable();
            $table->boolean('disetujui')->nullable();
            $table->timestamps();
            $table->dateTime('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cuti');
    }
};
