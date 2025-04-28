<?php

use App\Http\Controllers\AbsenController;
use App\Http\Controllers\CutiController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LogActivityController;
use App\Http\Controllers\RekapController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');


Route::middleware('auth')->group(function () {
    Route::get('home', [HomeController::class, 'index'])->name('home');
    Route::get('absen/load_tanggal', [AbsenController::class, 'load_tanggal'])->name('load_tanggal');
    Route::get('absen/load_detail', [AbsenController::class, 'load_detail'])->name('load_detail');
    Route::post('absen/store/', [AbsenController::class, 'store'])->name('absen.store');
    Route::get('absen/load_agenda', [AbsenController::class, 'load_agenda'])->name('absen.load_agenda');
    Route::post('absen/store_perbaikan/', [AbsenController::class, 'store_perbaikan'])->name('absen.store_perbaikan');
    Route::get('absen/load_perbaikan', [AbsenController::class, 'load_perbaikan'])->name('absen.load_perbaikan');
    Route::get('rekap/detail_user', [RekapController::class, 'detail_user'])->name('rekap.detail_user');
    Route::get('rekap/load_detail_user', [RekapController::class, 'load_detail_user'])->name('rekap.load_detail_user');
    Route::get('rekap/load_rekap', [RekapController::class, 'load_rekap'])->name('rekap.load_rekap');
    Route::get('rekap/load_rekap_user', [RekapController::class, 'load_rekap_user'])->name('rekap.load_rekap_user');
    Route::get('cuti/detail_user', [CutiController::class, 'detail_user'])->name('cuti.detail_user');
    Route::get('cuti/load_detail', [CutiController::class, 'load_detail'])->name('cuti.load_detail');
    Route::get('cuti/load_detail_pengajuan', [CutiController::class, 'load_detail_pengajuan'])->name('cuti.load_detail_pengajuan');
    Route::post('cuti/store', [CutiController::class, 'store'])->name('cuti.store');
    Route::post('cuti/persetujuan', [CutiController::class, 'persetujuan'])->name('cuti.persetujuan');
    Route::get('cuti', [CutiController::class, 'index'])->name('cuti');
    Route::delete('cuti/destroy/', [CutiController::class, 'destroy'])->name('cuti.destroy');
    Route::get('users', [UsersController::class, 'index'])->name('users');
    Route::get('users/load_data', [UsersController::class, 'load_data'])->name('users.load_data');
    Route::get('users/show', [UsersController::class, 'show'])->name('users.show');
    Route::post('users/store/', [UsersController::class, 'store'])->name('users.store');
    Route::get('logactivity', [LogActivityController::class, 'index'])->name('logactivity');
    Route::get('logactivity/load_data', [LogActivityController::class, 'load_data'])->name('logactivity.load_data');
});
Route::get('cuti/permohonan', [CutiController::class, 'permohonan'])->name('cuti.permohonan');
Route::post('cuti/store_permohonan', [CutiController::class, 'store_permohonan'])->name('cuti.permohonan.store');

// API
Route::get('cuti/get_user_cuti', [CutiController::class, 'get_user_cuti'])->name('api.cuti.get_user_cuti');
require __DIR__.'/auth.php';
