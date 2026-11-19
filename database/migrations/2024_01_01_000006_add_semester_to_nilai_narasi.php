<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel nilai
        if (Schema::hasTable('nilai')) {
            Schema::table('nilai', function (Blueprint $table) {
                if (!Schema::hasColumn('nilai', 'semester')) {
                    $table->string('semester', 10)->default('1')->after('nilai_uas');
                }
                if (!Schema::hasColumn('nilai', 'tahun_ajaran')) {
                    $table->string('tahun_ajaran', 15)->default('2024/2025')->after('semester');
                }
            });
        }

        // Tabel narasi (jika ada)
        if (Schema::hasTable('narasi')) {
            Schema::table('narasi', function (Blueprint $table) {
                if (!Schema::hasColumn('narasi', 'semester')) {
                    $table->string('semester', 10)->default('1')->after('narasi');
                }
                if (!Schema::hasColumn('narasi', 'tahun_ajaran')) {
                    $table->string('tahun_ajaran', 15)->default('2024/2025')->after('semester');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('nilai')) {
            Schema::table('nilai', function (Blueprint $table) {
                $table->dropColumn(['semester', 'tahun_ajaran']);
            });
        }

        if (Schema::hasTable('narasi')) {
            Schema::table('narasi', function (Blueprint $table) {
                $table->dropColumn(['semester', 'tahun_ajaran']);
            });
        }
    }
};