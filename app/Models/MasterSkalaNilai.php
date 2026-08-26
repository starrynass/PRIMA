<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterSkalaNilai extends Model
{
    use HasFactory;

    protected $table = 'dp3_master_skala_nilai';
    protected $primaryKey = 'skala_id';
}
