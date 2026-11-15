<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\MapelController;
use App\Http\Controllers\TujuanPembelajaranController;
use App\Http\Controllers\NilaiController;
use App\Http\Controllers\NarasiController;
use App\Http\Controllers\AturanPredikatController;

// Public
Route::post('/login', [AuthController::class, 'login']);

// Protected
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);

    // Siswa
    Route::apiResource('siswa', SiswaController::class);

    // Mata Pelajaran
    Route::apiResource('mapel', MapelController::class);

    // Tujuan Pembelajaran
    Route::get('/tp', [TujuanPembelajaranController::class, 'index']);
    Route::get('/tp/{id}', [TujuanPembelajaranController::class, 'show']);
    Route::post('/tp', [TujuanPembelajaranController::class, 'store']);
    Route::put('/tp/{id}', [TujuanPembelajaranController::class, 'update']);
    Route::delete('/tp/{id}', [TujuanPembelajaranController::class, 'destroy']);

    // Nilai
    Route::get('/nilai', [NilaiController::class, 'index']);
    Route::post('/nilai', [NilaiController::class, 'store']);
    Route::put('/nilai/{id}', [NilaiController::class, 'update']);

    // Narasi Capaian
    Route::get('/narasi', [NarasiController::class, 'index']);
    Route::post('/narasi/generate', [NarasiController::class, 'generate']);
    Route::put('/narasi/{id}', [NarasiController::class, 'update']);
    Route::put('/narasi/{id}/approve', [NarasiController::class, 'approve']);

    // Aturan Predikat (Rule-Based)
    Route::get('/aturan', [AturanPredikatController::class, 'index']);
    Route::post('/aturan', [AturanPredikatController::class, 'store']);
    Route::put('/aturan/{id}', [AturanPredikatController::class, 'update']);
    Route::delete('/aturan/{id}', [AturanPredikatController::class, 'destroy']);
});
