<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubDepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $subdepartments = [
            ['subdept_id' => 1,  'subdept_code' => null, 'subdept_name' => 'Direksi',                    'dept_id' => 1,  'isaktif' => 1, 'dept_name1' => null,   'perkiraan_gaji' => null, 'perkiraan_tunjangan' => null, 'jenis_adm_tek' => 0],
            ['subdept_id' => 2,  'subdept_code' => null, 'subdept_name' => 'Adm.&Keu.',                   'dept_id' => 2,  'isaktif' => 1, 'dept_name1' => null,   'perkiraan_gaji' => null, 'perkiraan_tunjangan' => null, 'jenis_adm_tek' => 0],
            ['subdept_id' => 3,  'subdept_code' => null, 'subdept_name' => 'Adm.SDM',                     'dept_id' => 14, 'isaktif' => 1, 'dept_name1' => null,   'perkiraan_gaji' => null, 'perkiraan_tunjangan' => null, 'jenis_adm_tek' => 0],
            ['subdept_id' => 4,  'subdept_code' => null, 'subdept_name' => 'Adm.Umum & Kearsipan',         'dept_id' => 17, 'isaktif' => 1, 'dept_name1' => null,   'perkiraan_gaji' => null, 'perkiraan_tunjangan' => null, 'jenis_adm_tek' => 0],
            ['subdept_id' => 5,  'subdept_code' => null, 'subdept_name' => 'Akuntansi & Perpajakan',       'dept_id' => 5,  'isaktif' => 1, 'dept_name1' => null,   'perkiraan_gaji' => null, 'perkiraan_tunjangan' => null, 'jenis_adm_tek' => 0],
            ['subdept_id' => 6,  'subdept_code' => null, 'subdept_name' => 'Anggaran & Pelaporan',         'dept_id' => 5,  'isaktif' => 1, 'dept_name1' => null,   'perkiraan_gaji' => null, 'perkiraan_tunjangan' => null, 'jenis_adm_tek' => 0],
            ['subdept_id' => 7,  'subdept_code' => null, 'subdept_name' => 'Cab Unit',                    'dept_id' => 2,  'isaktif' => 1, 'dept_name1' => null,   'perkiraan_gaji' => null, 'perkiraan_tunjangan' => null, 'jenis_adm_tek' => 0],
            ['subdept_id' => 8,  'subdept_code' => null, 'subdept_name' => 'Cabang',                      'dept_id' => 2,  'isaktif' => 1, 'dept_name1' => null,   'perkiraan_gaji' => null, 'perkiraan_tunjangan' => null, 'jenis_adm_tek' => 0],
            ['subdept_id' => 9,  'subdept_code' => null, 'subdept_name' => 'Data & Pelaporan',             'dept_id' => 13, 'isaktif' => 1, 'dept_name1' => null,   'perkiraan_gaji' => null, 'perkiraan_tunjangan' => null, 'jenis_adm_tek' => 0],
            ['subdept_id' => 10, 'subdept_code' => null, 'subdept_name' => 'Distribusi & NRW',            'dept_id' => 3,  'isaktif' => 1, 'dept_name1' => null,   'perkiraan_gaji' => null, 'perkiraan_tunjangan' => null, 'jenis_adm_tek' => 0],
            ['subdept_id' => 11, 'subdept_code' => null, 'subdept_name' => 'Evaluasi & Adm Teknik',        'dept_id' => 11, 'isaktif' => 1, 'dept_name1' => null,   'perkiraan_gaji' => null, 'perkiraan_tunjangan' => null, 'jenis_adm_tek' => 0],
            ['subdept_id' => 12, 'subdept_code' => null, 'subdept_name' => 'GIS',                         'dept_id' => 7,  'isaktif' => 1, 'dept_name1' => null,   'perkiraan_gaji' => null, 'perkiraan_tunjangan' => null, 'jenis_adm_tek' => 0],
            ['subdept_id' => 13, 'subdept_code' => null, 'subdept_name' => 'Hardware & Network',          'dept_id' => 7,  'isaktif' => 1, 'dept_name1' => null,   'perkiraan_gaji' => null, 'perkiraan_tunjangan' => null, 'jenis_adm_tek' => 0],
            ['subdept_id' => 14, 'subdept_code' => null, 'subdept_name' => 'Hublang',                     'dept_id' => 2,  'isaktif' => 1, 'dept_name1' => null,   'perkiraan_gaji' => null, 'perkiraan_tunjangan' => null, 'jenis_adm_tek' => 0],
            ['subdept_id' => 15, 'subdept_code' => null, 'subdept_name' => 'Hukum',                       'dept_id' => 15, 'isaktif' => 1, 'dept_name1' => null,   'perkiraan_gaji' => null, 'perkiraan_tunjangan' => null, 'jenis_adm_tek' => 0],
            ['subdept_id' => 16, 'subdept_code' => null, 'subdept_name' => 'Humas',                       'dept_id' => 9,  'isaktif' => 1, 'dept_name1' => null,   'perkiraan_gaji' => null, 'perkiraan_tunjangan' => null, 'jenis_adm_tek' => 0],
            ['subdept_id' => 17, 'subdept_code' => null, 'subdept_name' => 'Instalasi',                   'dept_id' => 4,  'isaktif' => 1, 'dept_name1' => null,   'perkiraan_gaji' => null, 'perkiraan_tunjangan' => null, 'jenis_adm_tek' => 0],
            ['subdept_id' => 18, 'subdept_code' => null, 'subdept_name' => 'K3',                          'dept_id' => 10, 'isaktif' => 1, 'dept_name1' => null,   'perkiraan_gaji' => null, 'perkiraan_tunjangan' => null, 'jenis_adm_tek' => 0],
            ['subdept_id' => 19, 'subdept_code' => null, 'subdept_name' => 'Keuangan',                    'dept_id' => 5,  'isaktif' => 1, 'dept_name1' => null,   'perkiraan_gaji' => null, 'perkiraan_tunjangan' => null, 'jenis_adm_tek' => 0],
            ['subdept_id' => 20, 'subdept_code' => null, 'subdept_name' => 'Konstruksi',                  'dept_id' => 8,  'isaktif' => 1, 'dept_name1' => null,   'perkiraan_gaji' => null, 'perkiraan_tunjangan' => null, 'jenis_adm_tek' => 0],
            ['subdept_id' => 21, 'subdept_code' => null, 'subdept_name' => 'Laboratorium',                'dept_id' => 10, 'isaktif' => 1, 'dept_name1' => null,   'perkiraan_gaji' => null, 'perkiraan_tunjangan' => null, 'jenis_adm_tek' => 0],
            ['subdept_id' => 22, 'subdept_code' => null, 'subdept_name' => 'Layanan Pelanggan',           'dept_id' => 9,  'isaktif' => 1, 'dept_name1' => null,   'perkiraan_gaji' => null, 'perkiraan_tunjangan' => null, 'jenis_adm_tek' => 0],
            ['subdept_id' => 23, 'subdept_code' => null, 'subdept_name' => 'Layanan Pengadaan',           'dept_id' => 6,  'isaktif' => 1, 'dept_name1' => null,   'perkiraan_gaji' => null, 'perkiraan_tunjangan' => null, 'jenis_adm_tek' => 0],
            ['subdept_id' => 24, 'subdept_code' => null, 'subdept_name' => 'Logistik',                    'dept_id' => 17, 'isaktif' => 1, 'dept_name1' => null,   'perkiraan_gaji' => null, 'perkiraan_tunjangan' => null, 'jenis_adm_tek' => 0],
            ['subdept_id' => 25, 'subdept_code' => null, 'subdept_name' => 'Manajer',                     'dept_id' => 2,  'isaktif' => 1, 'dept_name1' => null,   'perkiraan_gaji' => null, 'perkiraan_tunjangan' => null, 'jenis_adm_tek' => 0],
            ['subdept_id' => 26, 'subdept_code' => null, 'subdept_name' => 'MIS',                         'dept_id' => 7,  'isaktif' => 1, 'dept_name1' => null,   'perkiraan_gaji' => null, 'perkiraan_tunjangan' => null, 'jenis_adm_tek' => 0],
            ['subdept_id' => 27, 'subdept_code' => null, 'subdept_name' => 'Non Konstruksi',              'dept_id' => 8,  'isaktif' => 1, 'dept_name1' => null,   'perkiraan_gaji' => null, 'perkiraan_tunjangan' => null, 'jenis_adm_tek' => 0],
            ['subdept_id' => 28, 'subdept_code' => null, 'subdept_name' => 'Non Tender',                  'dept_id' => 6,  'isaktif' => 1, 'dept_name1' => null,   'perkiraan_gaji' => null, 'perkiraan_tunjangan' => null, 'jenis_adm_tek' => 0],
            ['subdept_id' => 29, 'subdept_code' => null, 'subdept_name' => 'NRW',                         'dept_id' => 3,  'isaktif' => 1, 'dept_name1' => null,   'perkiraan_gaji' => null, 'perkiraan_tunjangan' => null, 'jenis_adm_tek' => 0],
            ['subdept_id' => 30, 'subdept_code' => null, 'subdept_name' => 'Organisasi',                  'dept_id' => 14, 'isaktif' => 1, 'dept_name1' => null,   'perkiraan_gaji' => null, 'perkiraan_tunjangan' => null, 'jenis_adm_tek' => 0],
            ['subdept_id' => 31, 'subdept_code' => null, 'subdept_name' => 'Pelaksana Kegiatan',          'dept_id' => 8,  'isaktif' => 1, 'dept_name1' => null,   'perkiraan_gaji' => null, 'perkiraan_tunjangan' => null, 'jenis_adm_tek' => 0],
            ['subdept_id' => 32, 'subdept_code' => null, 'subdept_name' => 'Pemasaran',                   'dept_id' => 9,  'isaktif' => 1, 'dept_name1' => null,   'perkiraan_gaji' => null, 'perkiraan_tunjangan' => null, 'jenis_adm_tek' => 0],
            ['subdept_id' => 33, 'subdept_code' => null, 'subdept_name' => 'Pemasaran&Humas',             'dept_id' => 9,  'isaktif' => 1, 'dept_name1' => null,   'perkiraan_gaji' => null, 'perkiraan_tunjangan' => null, 'jenis_adm_tek' => 0],
            ['subdept_id' => 34, 'subdept_code' => null, 'subdept_name' => 'Pemeliharaan Jaringan',       'dept_id' => 3,  'isaktif' => 1, 'dept_name1' => null,   'perkiraan_gaji' => null, 'perkiraan_tunjangan' => null, 'jenis_adm_tek' => 0],
            ['subdept_id' => 35, 'subdept_code' => null, 'subdept_name' => 'Pemeliharaan ME',             'dept_id' => 12, 'isaktif' => 1, 'dept_name1' => null,   'perkiraan_gaji' => null, 'perkiraan_tunjangan' => null, 'jenis_adm_tek' => 0],
            ['subdept_id' => 36, 'subdept_code' => null, 'subdept_name' => 'Pemeliharaan Meter',          'dept_id' => 3,  'isaktif' => 1, 'dept_name1' => null,   'perkiraan_gaji' => null, 'perkiraan_tunjangan' => null, 'jenis_adm_tek' => 0],
            ['subdept_id' => 37, 'subdept_code' => null, 'subdept_name' => 'Penerimaan & Pengeluaran',    'dept_id' => 5,  'isaktif' => 1, 'dept_name1' => null,   'perkiraan_gaji' => null, 'perkiraan_tunjangan' => null, 'jenis_adm_tek' => 0],
            ['subdept_id' => 38, 'subdept_code' => null, 'subdept_name' => 'Peng,Investasi&Kerjasama',   'dept_id' => 13, 'isaktif' => 1, 'dept_name1' => null,   'perkiraan_gaji' => null, 'perkiraan_tunjangan' => null, 'jenis_adm_tek' => 0],
            ['subdept_id' => 39, 'subdept_code' => null, 'subdept_name' => 'Pengaturan Distribusi',       'dept_id' => 3,  'isaktif' => 1, 'dept_name1' => null,   'perkiraan_gaji' => null, 'perkiraan_tunjangan' => null, 'jenis_adm_tek' => 0],
            ['subdept_id' => 40, 'subdept_code' => null, 'subdept_name' => 'Pengawasan Adm.&Keu.',        'dept_id' => 16, 'isaktif' => 1, 'dept_name1' => null,   'perkiraan_gaji' => null, 'perkiraan_tunjangan' => null, 'jenis_adm_tek' => 0],
            ['subdept_id' => 41, 'subdept_code' => null, 'subdept_name' => 'Pengawasan Fisik',            'dept_id' => 16, 'isaktif' => 1, 'dept_name1' => null,   'perkiraan_gaji' => null, 'perkiraan_tunjangan' => null, 'jenis_adm_tek' => 0],
            ['subdept_id' => 42, 'subdept_code' => null, 'subdept_name' => 'Pengawasan Operasional',      'dept_id' => 16, 'isaktif' => 1, 'dept_name1' => null,   'perkiraan_gaji' => null, 'perkiraan_tunjangan' => null, 'jenis_adm_tek' => 0],
            ['subdept_id' => 43, 'subdept_code' => null, 'subdept_name' => 'Pengawasan SDM',              'dept_id' => 16, 'isaktif' => 1, 'dept_name1' => null,   'perkiraan_gaji' => null, 'perkiraan_tunjangan' => null, 'jenis_adm_tek' => 0],
            ['subdept_id' => 44, 'subdept_code' => null, 'subdept_name' => 'Pengelolaan Aset',            'dept_id' => 17, 'isaktif' => 1, 'dept_name1' => null,   'perkiraan_gaji' => null, 'perkiraan_tunjangan' => null, 'jenis_adm_tek' => 0],
            ['subdept_id' => 45, 'subdept_code' => null, 'subdept_name' => 'Pengelolaan Sumber',          'dept_id' => 12, 'isaktif' => 1, 'dept_name1' => null,   'perkiraan_gaji' => null, 'perkiraan_tunjangan' => null, 'jenis_adm_tek' => 0],
            ['subdept_id' => 46, 'subdept_code' => null, 'subdept_name' => 'Pengembangan SDM',           'dept_id' => 14, 'isaktif' => 1, 'dept_name1' => null,   'perkiraan_gaji' => null, 'perkiraan_tunjangan' => null, 'jenis_adm_tek' => 0],
            ['subdept_id' => 47, 'subdept_code' => null, 'subdept_name' => 'Penjaminan Mutu',            'dept_id' => 10, 'isaktif' => 1, 'dept_name1' => null,   'perkiraan_gaji' => null, 'perkiraan_tunjangan' => null, 'jenis_adm_tek' => 0],
            ['subdept_id' => 48, 'subdept_code' => null, 'subdept_name' => 'Penjaminan Mutu & K3',       'dept_id' => 10, 'isaktif' => 1, 'dept_name1' => null,   'perkiraan_gaji' => null, 'perkiraan_tunjangan' => null, 'jenis_adm_tek' => 0],
            ['subdept_id' => 49, 'subdept_code' => null, 'subdept_name' => 'Pertek',                      'dept_id' => 11, 'isaktif' => 1, 'dept_name1' => null,   'perkiraan_gaji' => null, 'perkiraan_tunjangan' => null, 'jenis_adm_tek' => 0],
            ['subdept_id' => 50, 'subdept_code' => null, 'subdept_name' => 'Produksi',                    'dept_id' => 12, 'isaktif' => 1, 'dept_name1' => null,   'perkiraan_gaji' => null, 'perkiraan_tunjangan' => null, 'jenis_adm_tek' => 0],
            ['subdept_id' => 51, 'subdept_code' => null, 'subdept_name' => 'Proses Produksi',            'dept_id' => 12, 'isaktif' => 1, 'dept_name1' => null,   'perkiraan_gaji' => null, 'perkiraan_tunjangan' => null, 'jenis_adm_tek' => 0],
            ['subdept_id' => 52, 'subdept_code' => null, 'subdept_name' => 'Renbang Operasional',        'dept_id' => 13, 'isaktif' => 1, 'dept_name1' => null,   'perkiraan_gaji' => null, 'perkiraan_tunjangan' => null, 'jenis_adm_tek' => 0],
            ['subdept_id' => 53, 'subdept_code' => null, 'subdept_name' => 'Sarana & Prasarana',         'dept_id' => 17, 'isaktif' => 1, 'dept_name1' => null,   'perkiraan_gaji' => null, 'perkiraan_tunjangan' => null, 'jenis_adm_tek' => 0],
            ['subdept_id' => 54, 'subdept_code' => null, 'subdept_name' => 'SDM',                         'dept_id' => 14, 'isaktif' => 1, 'dept_name1' => null,   'perkiraan_gaji' => null, 'perkiraan_tunjangan' => null, 'jenis_adm_tek' => 0],
            ['subdept_id' => 55, 'subdept_code' => null, 'subdept_name' => 'Sekretariat Perusahaan',     'dept_id' => 15, 'isaktif' => 1, 'dept_name1' => null,   'perkiraan_gaji' => null, 'perkiraan_tunjangan' => null, 'jenis_adm_tek' => 0],
            ['subdept_id' => 56, 'subdept_code' => null, 'subdept_name' => 'Software & Database',        'dept_id' => 7,  'isaktif' => 1, 'dept_name1' => null,   'perkiraan_gaji' => null, 'perkiraan_tunjangan' => null, 'jenis_adm_tek' => 0],
            ['subdept_id' => 57, 'subdept_code' => null, 'subdept_name' => 'SPI',                         'dept_id' => 16, 'isaktif' => 1, 'dept_name1' => null,   'perkiraan_gaji' => null, 'perkiraan_tunjangan' => null, 'jenis_adm_tek' => 0],
            ['subdept_id' => 58, 'subdept_code' => null, 'subdept_name' => 'Teknik',                      'dept_id' => 2,  'isaktif' => 1, 'dept_name1' => null,   'perkiraan_gaji' => null, 'perkiraan_tunjangan' => null, 'jenis_adm_tek' => 0],
            ['subdept_id' => 59, 'subdept_code' => null, 'subdept_name' => 'Tender',                      'dept_id' => 6,  'isaktif' => 1, 'dept_name1' => null,   'perkiraan_gaji' => null, 'perkiraan_tunjangan' => null, 'jenis_adm_tek' => 0],
            ['subdept_id' => 60, 'subdept_code' => null, 'subdept_name' => 'TU Direksi & Protokol',       'dept_id' => 15, 'isaktif' => 1, 'dept_name1' => null,   'perkiraan_gaji' => null, 'perkiraan_tunjangan' => null, 'jenis_adm_tek' => 0],
            ['subdept_id' => 61, 'subdept_code' => null, 'subdept_name' => 'Tunggakan & Penagihan',       'dept_id' => 9,  'isaktif' => 1, 'dept_name1' => null,   'perkiraan_gaji' => null, 'perkiraan_tunjangan' => null, 'jenis_adm_tek' => 0],
            ['subdept_id' => 62, 'subdept_code' => '62',  'subdept_name' => 'Umum',                       'dept_id' => 17, 'isaktif' => 1, 'dept_name1' => 'Umum', 'perkiraan_gaji' => '0',  'perkiraan_tunjangan' => '0',  'jenis_adm_tek' => 0],
        ];

        // Sesuaikan nama tabel 'sub_department' dengan nama tabel di database kamu
        DB::table('departement_sub')->insert($subdepartments);
    }
}