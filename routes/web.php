<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\Pasien;
use App\Http\Controllers\Dokter;
use App\Http\Controllers\Perawat;
use App\Http\Controllers\Admin;

// ── PUBLIC ROUTES ──────────────────────────────────────────
Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/layanan', [PublicController::class, 'layanan'])->name('layanan');
Route::get('/fasilitas', [PublicController::class, 'fasilitas'])->name('fasilitas');
Route::get('/kontak', [PublicController::class, 'kontak'])->name('kontak');

// ── AUTH ROUTES ────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/forgot-password', [AuthController::class, 'showForgot'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendReset'])->name('password.email');
});
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/profil', [\App\Http\Controllers\ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profil', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
});
// ── PASIEN ROUTES ──────────────────────────────────────────
Route::middleware(['auth', 'role:pasien'])->prefix('pasien')->name('pasien.')->group(function () {
    Route::get('/dashboard', [Pasien\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/bantuan', [Pasien\DashboardController::class, 'bantuan'])->name('bantuan');
    Route::resource('antrian', Pasien\AntrianController::class)->only(['index', 'create', 'store', 'show', 'update']);
    Route::get('rekam-medis', [Pasien\RekamMedisController::class, 'index'])->name('rekam-medis.index');
    Route::get('rekam-medis/{id}', [Pasien\RekamMedisController::class, 'show'])->name('rekam-medis.show');
    Route::get('rekam-medis/{id}/pdf', [Pasien\RekamMedisController::class, 'downloadPdf'])->name('rekam-medis.pdf');
    Route::resource('konsultasi', Pasien\KonsultasiController::class)->only(['index', 'create', 'store', 'show', 'update']);
    Route::get('konsultasi/cek-duplikat', [Pasien\KonsultasiController::class, 'cekDuplikat'])->name('konsultasi.cek-duplikat');
    Route::resource('pembayaran', Pasien\PembayaranController::class)->only(['index', 'show', 'update']);
    Route::post('pembayaran/{id}/upload', [Pasien\PembayaranController::class, 'uploadBukti'])->name('pembayaran.upload');
});

// ── DOKTER ROUTES ──────────────────────────────────────────
Route::middleware(['auth', 'role:dokter'])->prefix('dokter')->name('dokter.')->group(function () {
    Route::get('/dashboard', [Dokter\DashboardController::class, 'index'])->name('dashboard');
    Route::get('antrian', [Dokter\AntrianController::class, 'index'])->name('antrian.index');
    Route::patch('antrian/{id}/status', [Dokter\AntrianController::class, 'updateStatus'])->name('antrian.status');
    Route::resource('rekam-medis', Dokter\RekamMedisController::class)->only(['index', 'create', 'store', 'show', 'edit', 'update']);
    Route::resource('konsultasi', Dokter\KonsultasiController::class)->only(['index', 'show', 'update']);
});

// ── PERAWAT ROUTES ─────────────────────────────────────────
Route::middleware(['auth', 'role:perawat'])->prefix('perawat')->name('perawat.')->group(function () {
    Route::get('/dashboard', [Perawat\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('antrian', Perawat\AntrianController::class)->only(['index', 'show', 'create', 'store']);
    Route::patch('antrian/{id}/panggil', [Perawat\AntrianController::class, 'panggil'])->name('antrian.panggil');
    Route::patch('antrian/{id}/vital-signs', [Perawat\AntrianController::class, 'simpanVitalSigns'])->name('antrian.vital-signs');
    Route::get('rekam-medis', [Perawat\RekamMedisController::class, 'index'])->name('rekam-medis.index');
    Route::get('rekam-medis/{id}', [Perawat\RekamMedisController::class, 'show'])->name('rekam-medis.show');
    Route::patch('rekam-medis/{id}/catatan', [Perawat\RekamMedisController::class, 'tambahCatatan'])->name('rekam-medis.catatan');
});

// ── ADMIN ROUTES ───────────────────────────────────────────
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('users', Admin\UserController::class);
    Route::patch('users/{id}/toggle-aktif', [Admin\UserController::class, 'toggleAktif'])->name('users.toggle');
    Route::resource('layanan', Admin\LayananController::class);
    Route::resource('fasilitas', Admin\FasilitasController::class)->parameters(['fasilitas' => 'fasilitas']);
    Route::get('pembayaran', [Admin\PembayaranController::class, 'index'])->name('pembayaran.index');
    Route::patch('pembayaran/{id}/verifikasi', [Admin\PembayaranController::class, 'verifikasi'])->name('pembayaran.verifikasi');
    Route::get('laporan', [Admin\LaporanController::class, 'index'])->name('laporan.index');
    Route::get('laporan/export', [Admin\LaporanController::class, 'export'])->name('laporan.export');
});
