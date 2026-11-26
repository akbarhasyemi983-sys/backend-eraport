<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MataPelajaran extends Model
{
    use HasFactory;

    protected $table = 'mata_pelajaran';

    protected $fillable = [
        'kode',
        'nama',
        'kelas',
        'fase',
        'guru_id',
    ];

    public function tujuanPembelajaran()
    {
        return $this->hasMany(TujuanPembelajaran::class, 'mapel_id');
    }

    public function guru()
    {
        return $this->belongsTo(User::class, 'guru_id');
    }
}
