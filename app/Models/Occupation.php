<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Occupation extends Model
{
    use HasFactory;

    protected $table = 'occupation'; // Jika nama tabel di DB adalah 'occupations'
    protected $primaryKey = 'occ_id';
    public $incrementing = true;
    
    protected $fillable = [
        'occ_name',
        'occ_alias',
        'sub_off_id',
        'urut',
        'ket',
        'is_pejabat',
        'is_aktif',
        'level',
    ];
}
