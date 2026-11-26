<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mata_pelajaran', function (Blueprint $table) {
            // Tambah kolom kode jika belum ada
            if (!Schema::hasColumn('mata_pelajaran', 'kode')) {
                $table->string('kode', 20)->after('id')->default('');
            }

            // Rename nama_mapel → nama jika kolom lama masih ada
            if (Schema::hasColumn('mata_pelajaran', 'nama_mapel') &&
                !Schema::hasColumn('mata_pelajaran', 'nama')) {
                $table->renameColumn('nama_mapel', 'nama');
            }

            // Tambah kolom fase jika belum ada
            if (!Schema::hasColumn('mata_pelajaran', 'fase')) {
                $table->string('fase', 5)->nullable()->after('kelas');
            }
        });

        // Isi kode otomatis dari nama untuk data lama (jika ada)
        DB::table('mata_pelajaran')
            ->where(function ($q) {
                $q->where('kode', '')->orWhereNull('kode');
            })
            ->orderBy('id')
            ->each(function ($row) {
                $kode = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $row->nama ?? ''), 0, 6));
                DB::table('mata_pelajaran')
                    ->where('id', $row->id)
                    ->update(['kode' => $kode ?: 'MAPEL' . $row->id]);
            });
    }

    public function down(): void
    {
        Schema::table('mata_pelajaran', function (Blueprint $table) {
            if (Schema::hasColumn('mata_pelajaran', 'kode')) {
                $table->dropColumn('kode');
            }
            if (Schema::hasColumn('mata_pelajaran', 'fase')) {
                $table->dropColumn('fase');
            }
        });
    }
};
