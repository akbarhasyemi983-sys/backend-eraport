<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\MapelController;
use App\Http\Controllers\NilaiController;
use App\Http\Controllers\NarasiController;
use App\Http\Controllers\AturanPredikatController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);

    // ===== SISWA (admin & guru) =====
    Route::apiResource('siswa', SiswaController::class);

    // ===== MATA PELAJARAN =====
    Route::get('/mapel', [MapelController::class, 'index']);
    Route::post('/mapel', [MapelController::class, 'store']);
    Route::get('/mapel/{id}', [MapelController::class, 'show']);
    Route::put('/mapel/{id}', [MapelController::class, 'update']);
    Route::delete('/mapel/{id}', [MapelController::class, 'destroy']);

    // ===== TUJUAN PEMBELAJARAN (nested under mapel) =====
    Route::get('/mapel/{mapelId}/tp', [MapelController::class, 'tpIndex']);
    Route::post('/mapel/{mapelId}/tp', [MapelController::class, 'tpStore']);
    Route::put('/mapel/{mapelId}/tp/{tpId}', [MapelController::class, 'tpUpdate']);
    Route::delete('/mapel/{mapelId}/tp/{tpId}', [MapelController::class, 'tpDestroy']);

    // ===== NILAI =====
    Route::get('/nilai', [NilaiController::class, 'index']);
    Route::post('/nilai', [NilaiController::class, 'store']);
    Route::put('/nilai/{id}', [NilaiController::class, 'update']);
    Route::delete('/nilai/{id}', [NilaiController::class, 'destroy']);

    // ===== NARASI =====
    Route::get('/narasi', [NarasiController::class, 'index']);
    Route::post('/narasi/generate', [NarasiController::class, 'generate']);
    Route::put('/narasi/{id}', [NarasiController::class, 'update']);
    Route::put('/narasi/{id}/approve', [NarasiController::class, 'approve']);

    // ===== ATURAN PREDIKAT (admin only) =====
    Route::get('/aturan-predikat', [AturanPredikatController::class, 'index']);
    Route::post('/aturan-predikat', [AturanPredikatController::class, 'store']);
    Route::put('/aturan-predikat/{id}', [AturanPredikatController::class, 'update']);
    Route::delete('/aturan-predikat/{id}', [AturanPredikatController::class, 'destroy']);
    Route::post('/aturan-predikat/reset-default', [AturanPredikatController::class, 'resetDefault']);

    // ===== MANAJEMEN USER (admin only) =====
    Route::get('/users', [UserController::class, 'index']);
    Route::post('/users', [UserController::class, 'store']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);
    Route::post('/users/{id}/reset-password', [UserController::class, 'resetPassword']);
});
