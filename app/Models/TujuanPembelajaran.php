<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TujuanPembelajaran extends Model
{
    protected $table = 'tujuan_pembelajaran';
    protected $fillable = ['mapel_id', 'kode', 'deskripsi'];

    public function mapel()
    {
        return $this->belongsTo(MataPelajaran::class, 'mapel_id');
    }
}
