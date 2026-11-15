<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nilai extends Model
{
    protected $table = 'nilai';
    protected $fillable = [
        'siswa_id', 'mapel_id',
        'nilai_tugas', 'nilai_uh', 'nilai_uts', 'nilai_uas',
        'nilai_akhir', 'predikat',
        'tahun_ajaran', 'semester',
    ];

    public function siswa() { return $this->belongsTo(Siswa::class, 'siswa_id'); }
    public function mapel() { return $this->belongsTo(MataPelajaran::class, 'mapel_id'); }
}
