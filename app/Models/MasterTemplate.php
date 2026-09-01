<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterTemplate extends Model
{
    use HasFactory;

    protected $table = 'dp3_master_template_penilaian'; 
    protected $primaryKey = 'template_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
    'template_id', 
    'nama_template', 
    'occ_id',
    'status_aktif',
    'created_by'
    ];

    public function kategoris()
    {
        return $this->hasMany(MasterKategori::class, 'template_id', 'template_id')->orderBy('urutan');
    }

    public function getOccIdArrayAttribute()
    {
        return $this->occ_id ? explode(',', $this->occ_id) : [];
    }

    public function getOccupationsAttribute()
    {
        return \App\Models\Occupation::whereIn('occ_id', $this->occ_id_array)->get();
    }
}
