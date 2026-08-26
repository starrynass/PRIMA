<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterPredikatNilai extends Model
{
    use HasFactory;

    protected $table = 'dp3_master_predikat_nilai';
    protected $primaryKey = 'predikat_id';
}
