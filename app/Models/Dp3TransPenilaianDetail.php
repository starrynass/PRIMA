<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dp3TransPenilaianDetail extends Model
{
    use HasFactory;

    protected $table = 'dp3_trans_penilaian_detail';
    protected $primaryKey = 'penilaian_detail_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'penilaian_id',
        'pertanyaan_id',
        'pertanyaan',
        'deskripsi',
        'bobot_pertanyaan_persen',
        'skala_id',
        'kode_nilai',
        'nama_nilai',
        'nilai_angka',
        'nilai_akhir',
        'catatan',
        'verifikasi_kode',
        'verifikator_id',
        'verif_kode_nilai',
        'verif_nama_nilai',
        'verif_nilai_angka',
        'verif_nilai_akhir',
        'verif_catatan',
        'verif_nilai_sebelumnya',
        'verif_alasan_koreksi',
        'verif_status',
        'verif_tanggal',
        'catatan_verifikasi',
        'created_by',
        'updated_by'
    ];

    public function penilaian()
    {
        return $this->belongsTo(Dp3TransPenilaian::class, 'penilaian_id', 'penilaian_id');
    }
}