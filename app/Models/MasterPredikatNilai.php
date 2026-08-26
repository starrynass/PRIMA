<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterPredikatNilai extends Model
{
    use HasFactory;

    protected $table = 'dp3_master_predikat_nilai';
    protected $primaryKey = 'predikat_id';
    public $incrementing = false;
    protected $keyType = 'string';

    // WAJIB ADA: Daftarkan semua kolom yang diizinkan untuk disimpan
    protected $fillable = [
        'predikat_id',
        'kode',
        'nilai_min',
        'nilai_max',
        'predikat',
        'created_by',
    ];
}
