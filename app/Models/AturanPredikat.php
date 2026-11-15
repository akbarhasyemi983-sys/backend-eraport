<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AturanPredikat extends Model
{
    protected $table = 'aturan_predikat';
    protected $fillable = ['mapel', 'tp_kode', 'predikat', 'narasi_template'];
}
