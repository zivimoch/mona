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
        Schema::create('shift_rules', function (Blueprint $table) {
            $table->id();
            $table->string('uuid');
            $table->integer('kode');
            $table->string('judul');
            $table->time('jam_masuk')->nullable();
            $table->time('jam_pulang')->nullable();
            $table->timestamps();
            $table->dateTime('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shift_rules');
    }
};
