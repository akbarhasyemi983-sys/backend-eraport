<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('siswa', function (Blueprint $table) {
            // Tambah NISN setelah kolom nis (jika belum ada)
            if (!Schema::hasColumn('siswa', 'nisn')) {
                $table->string('nisn', 20)->nullable()->after('nis');
            }
        });
    }

    public function down(): void
    {
        Schema::table('siswa', function (Blueprint $table) {
            if (Schema::hasColumn('siswa', 'nisn')) {
                $table->dropColumn('nisn');
            }
        });
    }
};
