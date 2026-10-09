<?php

use App\Http\Controllers\Api\Admin\KaryawanController;
use App\Http\Controllers\Api\Admin\PresensiController;
use App\Http\Controllers\Api\Admin\GajiPokokController;
use App\Http\Controllers\Api\Admin\GajiLemburController;
use App\Http\Controllers\Api\Admin\GajiBonusController;
use App\Http\Controllers\Api\Admin\PotonganGajiController;
use App\Http\Controllers\Api\Admin\PenggajianController;
use App\Http\Controllers\Api\Admin\LaporanPenggajianController;
use App\Http\Controllers\Api\Admin\DashboardController;
use App\Http\Controllers\Api\Admin\AktivitasKaryawanController;
use App\Http\Controllers\Api\Admin\DataProdukController;
use App\Http\Controllers\Api\Pimpinan\DashboardController as PimpinanDashboardController;
use App\Http\Controllers\Api\Pimpinan\KaryawanController as PimpinanKaryawanController;
use App\Http\Controllers\Api\KelolaUserController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/logout', [AuthController::class, 'logout']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Pimpinan Routes
Route::get('/pimpinan/dashboard', [PimpinanDashboardController::class, 'index'])->name('pimpinan.dashboard');
Route::get('/pimpinan/karyawan', [PimpinanKaryawanController::class, 'index'])->name('pimpinan.karyawan.index');
Route::get('/pimpinan/laporan-penggajian', [\App\Http\Controllers\Api\Pimpinan\LaporanPenggajianController::class, 'index'])->name('pimpinan.laporan_penggajian.index');
Route::get('/pimpinan/kelola-user', [KelolaUserController::class, 'indexPimpinan'])->name('pimpinan.kelola_user.index');
Route::post('/pimpinan/kelola-user', [KelolaUserController::class, 'store'])->name('pimpinan.kelola_user.store');
Route::put('/pimpinan/kelola-user/{id}', [KelolaUserController::class, 'update'])->name('pimpinan.kelola_user.update');
Route::delete('/pimpinan/kelola-user/{id}', [KelolaUserController::class, 'destroy'])->name('pimpinan.kelola_user.destroy');

// Admin Routes
Route::get('/dashboard', [DashboardController::class , 'index'])->name('dashboard.index');
Route::get('/data-produk', [DataProdukController::class , 'index'])->name('data_produk.index');
Route::post('/data-produk', [DataProdukController::class , 'store'])->name('data_produk.store');
Route::delete('/data-produk/{id}', [DataProdukController::class , 'destroy'])->name('data_produk.destroy');
Route::patch('/data-produk/{id}/toggle-status', [DataProdukController::class , 'toggleStatus'])->name('data_produk.toggle_status');
Route::get('/aktivitas-karyawan', [AktivitasKaryawanController::class , 'index'])->name('aktivitas_karyawan.index');
Route::post('/aktivitas-karyawan/batch', [AktivitasKaryawanController::class , 'batchStore'])->name('aktivitas_karyawan.batch_store');
Route::post('/aktivitas-karyawan', [AktivitasKaryawanController::class , 'store'])->name('aktivitas_karyawan.store');
Route::get('/karyawan', [KaryawanController::class , 'index'])->name('karyawan.index');
Route::post('/karyawan', [KaryawanController::class , 'store'])->name('karyawan.store');
Route::put('/karyawan/{id}', [KaryawanController::class , 'update'])->name('karyawan.update');
Route::patch('/karyawan/{id}/toggle-status', [KaryawanController::class , 'toggleStatus'])->name('karyawan.toggle_status');
Route::delete('/karyawan/{id}', [KaryawanController::class , 'destroy'])->name('karyawan.destroy');
Route::get('/presensi', [PresensiController::class , 'index'])->name('presensi.index');
Route::post('/presensi/koreksi', [PresensiController::class , 'storeKoreksi'])->name('presensi.store_koreksi');
Route::post('/presensi/update-inline', [PresensiController::class , 'updateInline'])->name('presensi.update_inline');
Route::get('/gaji-pokok', [GajiPokokController::class , 'index'])->name('gaji_pokok.index');
Route::post('/gaji-pokok', [GajiPokokController::class , 'store'])->name('gaji_pokok.store');
Route::put('/gaji-pokok/{id}', [GajiPokokController::class , 'updatePokok'])->name('gaji_pokok.update');
Route::patch('/gaji-pokok/{id}/toggle-status', [GajiPokokController::class , 'toggleStatusPokok'])->name('gaji_pokok.toggle_status');
Route::delete('/gaji-pokok/{id}', [GajiPokokController::class , 'destroyPokok'])->name('gaji_pokok.destroy');
Route::get('/gaji-lembur', [GajiLemburController::class , 'index'])->name('gaji_lembur.index');
Route::put('/gaji-lembur/{id}', [GajiPokokController::class , 'updateLembur'])->name('gaji_lembur.update');
Route::patch('/gaji-lembur/{id}/toggle-status', [GajiPokokController::class , 'toggleStatusLembur'])->name('gaji_lembur.toggle_status');
Route::delete('/gaji-lembur/{id}', [GajiPokokController::class , 'destroyLembur'])->name('gaji_lembur.destroy');
Route::get('/gaji-bonus', [GajiBonusController::class, 'index'])->name('gaji_bonus.index');
Route::post('/gaji-bonus', [GajiBonusController::class, 'store'])->name('gaji_bonus.store');
Route::put('/gaji-bonus/{id}', [GajiBonusController::class, 'update'])->name('gaji_bonus.update');
Route::delete('/gaji-bonus/{id}', [GajiBonusController::class, 'destroy'])->name('gaji_bonus.destroy');
Route::patch('/gaji-bonus/{id}/toggle-status', [GajiBonusController::class, 'toggleStatus'])->name('gaji_bonus.toggle_status');
Route::get('/potongan-gaji', [PotonganGajiController::class, 'index'])->name('potongan_gaji.index');
Route::post('/potongan-gaji', [PotonganGajiController::class, 'store'])->name('potongan_gaji.store');
Route::put('/potongan-gaji/{id}', [PotonganGajiController::class, 'update'])->name('potongan_gaji.update');
Route::patch('/potongan-gaji/{id}/toggle-status', [PotonganGajiController::class, 'toggleStatus'])->name('potongan_gaji.toggle_status');
Route::delete('/potongan-gaji/{id}', [PotonganGajiController::class, 'destroy'])->name('potongan_gaji.destroy');
Route::get('/penggajian', [PenggajianController::class , 'index'])->name('penggajian.index');
Route::redirect('/detail-penggajian', '/penggajian')->name('detail_penggajian.index');
Route::get('/laporan-penggajian', [LaporanPenggajianController::class , 'index'])->name('laporan_penggajian.index');
Route::get('/kelola-user', [KelolaUserController::class, 'indexAdmin'])->name('kelola_user.index');
Route::post('/kelola-user', [KelolaUserController::class, 'store'])->name('kelola_user.store');
Route::put('/kelola-user/{id}', [KelolaUserController::class, 'update'])->name('kelola_user.update');
Route::delete('/kelola-user/{id}', [KelolaUserController::class, 'destroy'])->name('kelola_user.destroy');
