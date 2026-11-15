<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Aturan narasi rule-based
        Schema::create('aturan_predikat', function (Blueprint $table) {
            $table->id();
            $table->string('mapel');
            $table->string('tp_kode')->nullable();
            $table->string('predikat', 1); // A, B, C, D
            $table->text('narasi_template');
            $table->timestamps();
        });

        // Narasi capaian pembelajaran per siswa
        Schema::create('narasi_capaian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa')->onDelete('cascade');
            $table->foreignId('mapel_id')->constrained('mata_pelajaran')->onDelete('cascade');
            $table->text('narasi');
            $table->boolean('is_approved')->default(false);
            $table->boolean('is_generated')->default(false); // dari rule-based
            $table->string('tahun_ajaran')->default('2025/2026');
            $table->string('semester')->default('Ganjil');
            $table->timestamps();

            $table->unique(['siswa_id', 'mapel_id', 'tahun_ajaran', 'semester']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('narasi_capaian');
        Schema::dropIfExists('aturan_predikat');
    }
};
