<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterSkalaNilai extends Model
{
    use HasFactory;

    protected $table = 'dp3_master_skala_nilai';
    protected $primaryKey = 'skala_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['skala_id', 'kode_nilai', 'nama_nilai', 'nilai_angka', 'deskripsi', 'created_by'];
}
