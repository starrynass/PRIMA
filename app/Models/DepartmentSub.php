<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepartmentSub extends Model
{
    use HasFactory;

    protected $table = 'department_sub';
    protected $primaryKey = 'subdept_id';

    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'subdept_code',
        'subdept_name',
        'dept_id',
        'isaktif',
        'dept_name1',
        'perkiraan_gaji',
        'perkiraan_tunjangan',
        'jenis_adm_tek',
    ];

    protected $casts = [
        'isaktif' => 'integer',
        'jenis_adm_tek' => 'integer',
    ];

    // Relasi Inverse ke Parent Department
    public function department()
    {
        return $this->belongsTo(Department::class, 'dept_id', 'dept_id');
    }

    // Relasi One-to-Many ke Employee (opsional)
    public function employees()
    {
        return $this->hasMany(Employee::class, 'subdept_id', 'subdept_id');
    }
}

