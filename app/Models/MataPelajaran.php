<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MataPelajaran extends Model
{
    protected $table = 'mata_pelajaran';
    protected $fillable = ['kode', 'nama', 'fase', 'kelas'];

    public function tujuanPembelajaran()
    {
        return $this->hasMany(TujuanPembelajaran::class, 'mapel_id');
    }
}
