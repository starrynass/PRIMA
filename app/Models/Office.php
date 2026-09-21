<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Office extends Model
{
    use HasFactory;

    protected $table = 'office'; 
    protected $primaryKey = 'off_id';

    // 2. Tipe primary key auto increment
    public $incrementing = true;
    protected $keyType = 'int';

    // 3. Kolom yang dapat diisi secara massal (mass assignable)
    protected $fillable = [
        'off_code',
        'off_name',
        'off_telp',
        'off_addr',
        'off_cp',
        'of_type',
        'online',
        'of_lat',
        'of_long',
        'radius',
    ];

    // 4. Relasi opsional ke pegawai
    public function employees()
    {
        return $this->hasMany(Employee::class, 'off_id', 'off_id');
    }
}
