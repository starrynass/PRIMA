<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dp3TransPenilaian extends Model
{
    protected $table = 'dp3_trans_penilaian';
    protected $primaryKey = 'penilaian_id';
    public $incrementing = true;
    protected $keyType = 'int';

    // Kolom yang dapat diisi (mass assignable)
    protected $fillable = [
        'kode',
        'periode_id',
        'pegawai_id',
        'pgw_nup',
        'pgw_nama',
        'pgw_sex',
        'pgw_id_status_pegawai',
        'pgw_stts_pgw',
        'pgw_id_jabatan',
        'pgw_kd_jabatan',
        'pgw_jabatan',
        'pgw_id_dept',
        'pgw_dept_name',
        'pgw_id_subdept',
        'pgw_id_subdept_name',
        'pgw_off_id',
        'pgw_off_name',
        'pgw_kode_satker',
        'pgw_id_golongan',
        'pgw_golongan',
        'pgw_mkg_y',
        'template_id',
        'template_nama',
        'penilai_id',
        'verifikator_id',
        'penilai_manual',
        'total_nilai',
        'total_nilai_verifikator',
        'predikat_id',
        'predikat',
        'predikat_verifikator',
        'kode_verifikasi',
        'catatan_verifikator',
        'catatan',
        'status_nilai',
        'status_verifitor',
        'tanggal_submit',
        'tanggal_verifikasi',
        'tanggal_revisi',
        'created_by',
    ];

    public function getLingkupKerjaAttribute(): string
    {
        return $this->unitKerja->lingkup_kerja ?? 'LAINNYA';
    }

    // Relasi ke Periode Penilaian
    public function periode()
    {
        return $this->belongsTo(Dp3TransPeriodePenilaian::class, 'periode_id', 'periode_id');
    }

    // Relasi ke Employee (Pegawai)
    public function pegawai()
    {
       return $this->belongsTo(Employee::class, 'pegawai_id', 'pgw_id');
    }

    // Relasi ke Penilai
    public function penilai()
    {
        return $this->belongsTo(Employee::class, 'penilai_id', 'id');
    }

    public function details()
    {
        return $this->hasMany(Dp3TransPenilaianDetail::class, 'penilaian_id', 'penilaian_id');
    }

    // Relasi ke Verifikator
public function verifikator()
{
    return $this->belongsTo(Employee::class, 'verifikator_id', 'pgw_id');
}

// Relasi ke Office (Penempatan)
public function office()
{
    return $this->belongsTo(Office::class, 'pgw_off_id', 'off_id');
}

// Relasi ke Department
public function department()
{
    return $this->belongsTo(Department::class, 'pgw_id_dept', 'dept_id');
}

public function occupation()
{
    return $this->belongsTo(Occupation::class, 'pgw_id_jabatan', 'occ_id'); 
}

public function unitkerja()
{
    return $this->belongsTo(UnitKerja::class, 'pgw_kode_unit_kerja', 'kode_unit_kerja'); 
}
}