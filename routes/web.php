<?php

use App\Http\Controllers\Api\Admin\KaryawanController;
use App\Http\Controllers\Api\Admin\PresensiController;
use App\Http\Controllers\Api\Admin\GajiPokokController;
use App\Http\Controllers\Api\Admin\GajiLemburController;
use App\Http\Controllers\Api\Admin\GajiBonusController;
use App\Http\Controllers\Api\Admin\PotonganGajiController;
use App\Http\Controllers\Api\Admin\PenggajianController;
use App\Http\Controllers\Api\Admin\DetailPenggajianController;
use App\Http\Controllers\Api\Admin\LaporanPenggajianController;
use App\Http\Controllers\Api\Pimpinan\DashboardController as PimpinanDashboardController;
use App\Http\Controllers\Api\Pimpinan\KaryawanController as PimpinanKaryawanController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Pimpinan Routes
Route::get('/pimpinan/dashboard', [PimpinanDashboardController::class, 'index'])->name('pimpinan.dashboard');
Route::get('/pimpinan/karyawan', [PimpinanKaryawanController::class, 'index'])->name('pimpinan.karyawan.index');
Route::get('/pimpinan/laporan-penggajian', [\App\Http\Controllers\Api\Pimpinan\LaporanPenggajianController::class, 'index'])->name('pimpinan.laporan_penggajian.index');

Route::get('/karyawan', [KaryawanController::class , 'index'])->name('karyawan.index');
Route::post('/karyawan', [KaryawanController::class , 'store'])->name('karyawan.store');
Route::put('/karyawan/{id}', [KaryawanController::class , 'update'])->name('karyawan.update');
Route::delete('/karyawan/{id}', [KaryawanController::class , 'destroy'])->name('karyawan.destroy');
Route::get('/presensi', [PresensiController::class , 'index'])->name('presensi.index');
Route::get('/gaji-pokok', [GajiPokokController::class , 'index'])->name('gaji_pokok.index');
Route::post('/gaji-pokok', [GajiPokokController::class , 'store'])->name('gaji_pokok.store');
Route::get('/gaji-lembur', [GajiLemburController::class , 'index'])->name('gaji_lembur.index');
Route::get('/gaji-bonus', [GajiBonusController::class , 'index'])->name('gaji_bonus.index');
Route::get('/potongan-gaji', [PotonganGajiController::class , 'index'])->name('potongan_gaji.index');
Route::get('/penggajian', [PenggajianController::class , 'index'])->name('penggajian.index');
Route::get('/detail-penggajian', [DetailPenggajianController::class , 'index'])->name('detail_penggajian.index');
Route::get('/laporan-penggajian', [LaporanPenggajianController::class , 'index'])->name('laporan_penggajian.index');
