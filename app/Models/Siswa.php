<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    protected $table = 'siswa';
    protected $fillable = ['nis','nisn', 'nama', 'kelas', 'jenis_kelamin', 'nama_ortu', 'no_hp_ortu', 'tahun_ajaran', 'semester'];
}
