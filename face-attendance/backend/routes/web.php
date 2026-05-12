<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\MahasiswaAbsenController;
use App\Http\Controllers\AbsensiController;

/** @noinspection PhpUndefinedFunctionInspection */

// Root redirect
Route::get('/', function () {
    return redirect('/login');
});

// ============================
//  AUTH (shared login page)
// ============================
Route::get('/login',  [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// ============================
//  MAHASISWA PORTAL
// ============================
Route::get('/absen',        [MahasiswaAbsenController::class, 'absenPage'])->name('mhs.absen');
Route::post('/absen/wajah', [MahasiswaAbsenController::class, 'absenWajah'])->name('mhs.absen.wajah');
Route::post('/absen/izin',  [MahasiswaAbsenController::class, 'absenIzin'])->name('mhs.absen.izin');
Route::get('/absen/riwayat', [MahasiswaAbsenController::class, 'riwayatPage'])->name('mhs.riwayat');

// ============================
//  ADMIN PANEL
// ============================
Route::middleware(['web'])->group(function () {
    Route::get('/dashboard',               [AuthController::class,    'dashboard'])->name('dashboard');
    Route::get('/mahasiswa',               [MahasiswaController::class, 'index'])->name('mahasiswa.index');
    Route::post('/mahasiswa/store',        [MahasiswaController::class, 'store'])->name('mahasiswa.store');
    Route::post('/mahasiswa/update/{id}',  [MahasiswaController::class, 'update'])->name('mahasiswa.update');
    Route::post('/mahasiswa/delete/{id}',  [MahasiswaController::class, 'destroy'])->name('mahasiswa.destroy');
    Route::get('/mahasiswa/capture/{nim}', [MahasiswaController::class, 'capturePage'])->name('mahasiswa.capture.page');
    Route::post('/mahasiswa/capture',      [MahasiswaController::class, 'captureRun'])->name('mahasiswa.capture.run');
    Route::get('/train',                   [MahasiswaController::class, 'trainModel'])->name('train.model');
    Route::get('/absensi',                 [AbsensiController::class,  'index'])->name('absensi.index');
});

// Legacy absen route (compat)
Route::get('/absen-admin',  [MahasiswaController::class, 'absenPage'])->name('absen.index');
Route::post('/absen-admin/run', [MahasiswaController::class, 'absenRun'])->name('absen.run');

// Test
Route::get('/test-session', function () {
    return config('session.driver');
});