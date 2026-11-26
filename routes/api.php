<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\MataPelajaranController;
use App\Http\Controllers\TujuanPembelajaranController;
use App\Http\Controllers\NilaiController;
use App\Http\Controllers\NarasiController;
use App\Http\Controllers\AturanPredikatController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SettingsController;

// ====== PUBLIC ROUTES ======
Route::post('/login', [AuthController::class, 'login']);

// ====== AUTHENTICATED ROUTES ======
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // ====== SETTINGS (all roles can read, only admin can update) ======
    Route::get('/settings', [SettingsController::class, 'index']);
    Route::get('/settings/{key}', [SettingsController::class, 'show']);
    Route::put('/settings', [SettingsController::class, 'update']); // admin only (enforced in controller or middleware)

    // ====== SISWA ======
    Route::get('/siswa', [SiswaController::class, 'index']);
    Route::get('/siswa/{id}', [SiswaController::class, 'show']);
    Route::post('/siswa', [SiswaController::class, 'store']);
    Route::put('/siswa/{id}', [SiswaController::class, 'update']);
    Route::delete('/siswa/{id}', [SiswaController::class, 'destroy']);

    // ====== MATA PELAJARAN ======
    Route::get('/mapel', [MataPelajaranController::class, 'index']);
    Route::get('/mapel/{id}', [MataPelajaranController::class, 'show']);
    Route::post('/mapel', [MataPelajaranController::class, 'store']);
    Route::put('/mapel/{id}', [MataPelajaranController::class, 'update']);
    Route::delete('/mapel/{id}', [MataPelajaranController::class, 'destroy']);

    // ====== TUJUAN PEMBELAJARAN ======
    Route::get('/mapel/{mapelId}/tp', [TujuanPembelajaranController::class, 'index']);
    Route::post('/mapel/{mapelId}/tp', [TujuanPembelajaranController::class, 'store']);
    Route::get('/mapel/{mapelId}/tp/{id}', [TujuanPembelajaranController::class, 'show']);
    Route::put('/mapel/{mapelId}/tp/{id}', [TujuanPembelajaranController::class, 'update']);
    Route::delete('/mapel/{mapelId}/tp/{id}', [TujuanPembelajaranController::class, 'destroy']);

    // ====== NILAI ======
    Route::get('/nilai', [NilaiController::class, 'index']);
    Route::get('/nilai/{id}', [NilaiController::class, 'show']);
    Route::post('/nilai', [NilaiController::class, 'store']);
    Route::put('/nilai/{id}', [NilaiController::class, 'update']);
    Route::delete('/nilai/{id}', [NilaiController::class, 'destroy']);

    // ====== NARASI CAPAIAN ======
    Route::get('/narasi', [NarasiController::class, 'index']);
    Route::get('/narasi/{id}', [NarasiController::class, 'show']);
    Route::post('/narasi/generate', [NarasiController::class, 'generate']);
    Route::put('/narasi/{id}', [NarasiController::class, 'update']);
    Route::put('/narasi/{id}/approve', [NarasiController::class, 'approve']);
    Route::delete('/narasi/{id}', [NarasiController::class, 'destroy']);

    // ====== ATURAN PREDIKAT ======
    Route::get('/aturan-predikat', [AturanPredikatController::class, 'index']);
    Route::post('/aturan-predikat', [AturanPredikatController::class, 'store']);
    Route::put('/aturan-predikat/{id}', [AturanPredikatController::class, 'update']);
    Route::delete('/aturan-predikat/{id}', [AturanPredikatController::class, 'destroy']);
    Route::post('/aturan-predikat/reset-default', [AturanPredikatController::class, 'resetDefault']);

    // ====== USER MANAGEMENT (admin only) ======
    Route::get('/users', [UserController::class, 'index']);
    Route::post('/users', [UserController::class, 'store']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);
    Route::post('/users/{id}/reset-password', [UserController::class, 'resetPassword']);
});
