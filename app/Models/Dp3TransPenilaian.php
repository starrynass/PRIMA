<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dp3TransPenilaian extends Model
{
    use HasFactory;

    protected $table = 'dp3_trans_penilaian';
    protected $primaryKey = 'penilaian_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $guarded = [];
}