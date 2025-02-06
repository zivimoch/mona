<?php

use App\Http\Controllers\AbsenController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RekapController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');


Route::middleware('auth')->group(function () {
    Route::get('home', [HomeController::class, 'index'])->name('home');
    Route::get('absen/load_tanggal', [AbsenController::class, 'load_tanggal'])->name('load_tanggal');
    Route::get('absen/load_detail', [AbsenController::class, 'load_detail'])->name('load_detail');
    Route::post('absen/store/', [AbsenController::class, 'store'])->name('absen.store');
    Route::get('rekap/detail_user', [RekapController::class, 'detail_user'])->name('rekap.detail_user');
    Route::get('rekap/load_detail_user', [RekapController::class, 'load_detail_user'])->name('rekap.load_detail_user');
});

require __DIR__.'/auth.php';
