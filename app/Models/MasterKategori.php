<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterKategori extends Model
{
    use HasFactory;

    protected $table = 'dp3_master_kategori_penilaian'; 
    protected $primaryKey = 'kategori_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kategori_id', 'template_id', 'kode', 'nama', 
        'bobot_persen', 'urutan', 'created_by', 'updated_by'
    ];

    public function template()
    {
        return $this->belongsTo(MasterTemplate::class, 'template_id', 'template_id');
    }

    public function pertanyaans()
    {
        return $this->hasMany(MasterPertanyaan::class, 'kategori_id', 'kategori_id')->orderBy('urutan');
    }
}
