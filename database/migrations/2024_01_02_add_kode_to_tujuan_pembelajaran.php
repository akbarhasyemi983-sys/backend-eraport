<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tujuan_pembelajaran', function (Blueprint $table) {
            // Tambah kolom kode (string) jika belum ada
            if (!Schema::hasColumn('tujuan_pembelajaran', 'kode')) {
                $table->string('kode', 20)->after('mapel_id')->default('');
            }
        });

        // Konversi nomor_tp → kode untuk data lama
        if (Schema::hasColumn('tujuan_pembelajaran', 'nomor_tp')) {
            DB::table('tujuan_pembelajaran')
                ->where(function ($q) {
                    $q->whereNull('kode')->orWhere('kode', '');
                })
                ->orderBy('id')
                ->each(function ($row) {
                    DB::table('tujuan_pembelajaran')
                        ->where('id', $row->id)
                        ->update(['kode' => (string)($row->nomor_tp ?? $row->id)]);
                });
        }
    }

    public function down(): void
    {
        Schema::table('tujuan_pembelajaran', function (Blueprint $table) {
            if (Schema::hasColumn('tujuan_pembelajaran', 'kode')) {
                $table->dropColumn('kode');
            }
        });
    }
};
