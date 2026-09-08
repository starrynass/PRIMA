<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    // 1. Deklarasikan nama tabel di database
    protected $table = 'employee';

    // 2. Deklarasikan Primary Key (karena bukan 'id' bawaan Laravel)
    protected $primaryKey = 'pgw_id';

    // 3. Jika primary key bertipe integer auto-increment
    public $incrementing = true;
    protected $keyType = 'int';

    // 4. Daftar kolom yang dapat diisi secara massal (Mass Assignment)
    protected $fillable = [
        'pgw_id',
        'nup',
        'nama',
        'sex',
        'tempat',
        'tgl_lahir',
        'tgl_masuk',
        'alamat',
        'nohp',
        'gol_darah',
        'off_id',
        'dept_id',
        'subdept_id',
        'occ_id',
        'jurusan_pendidikan',
        'thn_lulus',
        'no_npwp',
        'foto_pegawai',
        'username_mobile',
        'password_mobile',
        'is_pensiun',
    ];

    // 5. Sembunyikan atribut sensitif dari array/JSON response
    protected $hidden = [
        'password_mobile',
    ];

    // Optional: Relasi ke tabel Department jika dibutuhkan di kemudian hari
    public function department()
    {
        return $this->belongsTo(Department::class, 'dept_id', 'dept_id');
    }

    // Optional: Relasi ke tabel SubDepartment
    public function subDepartment()
    {
        return $this->belongsTo(SubDepartment::class, 'subdept_id', 'subdept_id');
    }

    // Optional: Relasi ke tabel Office
    public function office()
    {
        return $this->belongsTo(Office::class, 'off_id', 'off_id');
    }

    // Optional: Relasi ke tabel Occupation
    public function occupation()
    {
        return $this->belongsTo(Occupation::class, 'occ_id', 'occ_id');
    }
}