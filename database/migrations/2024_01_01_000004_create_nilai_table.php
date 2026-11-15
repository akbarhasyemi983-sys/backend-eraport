<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('nilai', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa')->onDelete('cascade');
            $table->foreignId('mapel_id')->constrained('mata_pelajaran')->onDelete('cascade');
            $table->decimal('nilai_tugas', 5, 2)->default(0);
            $table->decimal('nilai_uh', 5, 2)->default(0);   // Ulangan Harian
            $table->decimal('nilai_uts', 5, 2)->default(0);  // UTS
            $table->decimal('nilai_uas', 5, 2)->default(0);  // UAS
            $table->decimal('nilai_akhir', 5, 2)->nullable();
            $table->string('predikat', 1)->nullable(); // A, B, C, D
            $table->string('tahun_ajaran')->default('2025/2026');
            $table->string('semester')->default('Ganjil');
            $table->timestamps();

            $table->unique(['siswa_id', 'mapel_id', 'tahun_ajaran', 'semester']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nilai');
    }
};
