<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterPertanyaan extends Model
{
    use HasFactory;

    protected $table = 'dp3_master_pertanyaan_penilaian'; 
    protected $primaryKey = 'pertanyaan_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'pertanyaan_id', 'kategori_id', 'pertanyaan', 'deskripsi',
        'bobot_persen', 'urutan', 'jenis', 'status_aktif', 'created_by'
    ];

    public function kategori()
    {
        return $this->belongsTo(MasterKategori::class, 'kategori_id', 'kategori_id');
    }
}
