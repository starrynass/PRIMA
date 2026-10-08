<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UnitKerja extends Model
{
    use HasFactory;

    // 1. Nama tabel
    protected $table = 'unit_kerja';
    protected $primaryKey = 'kode_unit_kerja';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kode_unit_kerja',
        'nama_unit_kerja',
    ];

    /**
     * Relasi One-to-Many ke model Employee
     * Satu Unit Kerja memiliki banyak Employee
     */
    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class, 'kode_unit_kerja', 'kode_unit_kerja');
    }

    public function getLingkupKerjaAttribute(): string
    {
        $nama = trim($this->nama_unit_kerja ?? $this->nama_unit ?? '');
        $namaLower = strtolower($nama);

        // 1. Cek Kata Kunci Cabang
        if (str_contains($namaLower, 'cabang')) {
            return 'CABANG';
        }

        // 2. Cek Kata Kunci Instalasi
        if (str_contains($namaLower, 'instalasi') || str_contains($namaLower, 'unit ')) {
            return 'INSTALASI';
        }

        // 3. Cek Divisi Utama
        if ($this->containsAny($namaLower, ['spi', 'renbang', 'sekretariat', 'mis', 'pengawasan intern', 'perencanaan'])) {
            return 'DIVISI UTAMA';
        }

        // 4. Cek Divisi Umum
        if ($this->containsAny($namaLower, ['pemasaran', 'humas', 'keuangan', 'sdm', 'sumber daya', 'umum', 'pengadaan'])) {
            return 'DIVISI UMUM';
        }

        // 5. Cek Divisi Operasional
        if ($this->containsAny($namaLower, ['distribusi', 'nrw', 'produksi', 'penjaminan mutu', 'k3', 'pelaksana', 'rentek', 'teknik'])) {
            return 'DIVISI OPERASIONAL';
        }

        return 'LAINNYA';
    }

    /**
     * Helper kecil untuk mengecek apakah teks mengandung salah satu kata kunci
     */
    private function containsAny(string $haystack, array $needles): bool
    {
        foreach ($needles as $needle) {
            if (str_contains($haystack, $needle)) {
                return true;
            }
        }
        return false;
    }
}