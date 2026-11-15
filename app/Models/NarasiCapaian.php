<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NarasiCapaian extends Model
{
    protected $table = 'narasi_capaian';
    protected $fillable = [
        'siswa_id', 'mapel_id', 'narasi',
        'is_approved', 'is_generated',
        'tahun_ajaran', 'semester',
    ];

    protected $casts = [
        'is_approved'  => 'boolean',
        'is_generated' => 'boolean',
    ];

    public function siswa() { return $this->belongsTo(Siswa::class, 'siswa_id'); }
    public function mapel() { return $this->belongsTo(MataPelajaran::class, 'mapel_id'); }
}
