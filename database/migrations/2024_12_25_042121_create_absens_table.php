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
        Schema::create('absen', function (Blueprint $table) {
            $table->id();
            $table->string('uuid');
            $table->integer('user_id');
            $table->date('tanggal_masuk')->nullable();
            $table->time('jam_masuk')->nullable();
            $table->string('koordinat_masuk')->nullable();
            $table->string('foto_masuk')->nullable();
            $table->string('catatan_masuk')->nullable();
            $table->date('tanggal_pulang')->nullable();
            $table->time('jam_pulang')->nullable();
            $table->string('koordinat_pulang')->nullable();
            $table->string('foto_pulang')->nullable();
            $table->string('catatan_pulang')->nullable();
            $table->timestamps();
            $table->dateTime('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absen');
    }
};
