<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dp3TransPeriodePenilaian extends Model
{
    use HasFactory;

    protected $table = 'dp3_trans_periode_penilaian';
    protected $primaryKey = 'periode_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'periode_id',
        'bulan',
        'tahun',
        'tanggal_mulai',
        'tanggal_deadline',
        'status',
        'dibuka_oleh_admin',
        'created_by',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_deadline' => 'date',
        'bulan' => 'integer',
        'tahun' => 'integer',
    ];

    /**
     * Relasi ke Detail Transaksi Penilaian (Dp3TransPenilaian)
     */
    public function transPenilaian(): HasMany
    {
        // DIPERBAIKI: Menunjuk ke Model Dp3TransPenilaian::class
        return $this->hasMany(Dp3TransPenilaian::class, 'periode_id', 'periode_id');
    }

    /**
     * Accessor Nama Periode (Contoh: "2026 - Agustus")
     */
    public function getNamaPeriodeAttribute(): string
    {
        $namaBulan = match ($this->bulan) {
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
            default => '-',
        };

        return "{$this->tahun} - {$namaBulan}";
    }
}