<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $table = 'department';
    protected $primaryKey = 'dept_id';

    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'dept_code',
        'dept_name',
        'isaktif',
        'urut',
        'is_pusat',
        'urut_laporan',
        'urut_bulanan_laporan',
        'is_stakeholder',
    ];

    protected $casts = [
        'isaktif' => 'boolean',
        'is_pusat' => 'integer',
        'urut' => 'integer',
        'urut_laporan' => 'integer',
        'urut_bulanan_laporan' => 'integer',
        'is_stakeholder' => 'integer',
    ];

    // Relasi One-to-Many ke Sub Department
    public function subDepartments()
    {
        return $this->hasMany(SubDepartment::class, 'dept_id', 'dept_id');
    }

    // Relasi One-to-Many ke Employee (opsional)
    public function employees()
    {
        return $this->hasMany(Employee::class, 'dept_id', 'dept_id');
    }
}
