<?php

use App\Http\Controllers\AbsenController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');


Route::middleware('auth')->group(function () {
    Route::get('home', [HomeController::class, 'index'])->name('home');
    Route::get('absen/load_tanggal', [AbsenController::class, 'load_tanggal'])->name('load_tanggal');
});

require __DIR__.'/auth.php';
