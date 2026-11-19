<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tambah semester & tahun_ajaran ke tabel nilai
        Schema::table('nilai', function (Blueprint $table) {
            $table->string('semester', 10)->default('1')->after('nilai_uas');
            $table->string('tahun_ajaran', 15)->default('2024/2025')->after('semester');
        });

        // Tambah semester & tahun_ajaran ke tabel narasi_capaian
        Schema::table('narasi_capaian', function (Blueprint $table) {
            $table->string('semester', 10)->default('1')->after('status');
            $table->string('tahun_ajaran', 15)->default('2024/2025')->after('semester');
        });
    }

    public function down(): void
    {
        Schema::table('nilai', function (Blueprint $table) {
            $table->dropColumn(['semester', 'tahun_ajaran']);
        });
        Schema::table('narasi_capaian', function (Blueprint $table) {
            $table->dropColumn(['semester', 'tahun_ajaran']);
        });
    }
};
