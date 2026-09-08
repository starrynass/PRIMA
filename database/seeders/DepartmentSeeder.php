<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            ['dept_id' => 1,  'dept_code' => '1',  'dept_name' => 'Direksi',             'isaktif' => 1, 'urut' => 1,  'is_pusat' => 1, 'is_stakeholder' => 1],
            ['dept_id' => 2,  'dept_code' => '2',  'dept_name' => 'Cabang',              'isaktif' => 1, 'urut' => 2,  'is_pusat' => 0, 'is_stakeholder' => 0],
            ['dept_id' => 3,  'dept_code' => '3',  'dept_name' => 'Distribusi & NRW',     'isaktif' => 1, 'urut' => 3,  'is_pusat' => 1, 'is_stakeholder' => 0],
            ['dept_id' => 4,  'dept_code' => '4',  'dept_name' => 'Instalasi',           'isaktif' => 1, 'urut' => 4,  'is_pusat' => 0, 'is_stakeholder' => 0],
            ['dept_id' => 5,  'dept_code' => '5',  'dept_name' => 'Keuangan',            'isaktif' => 1, 'urut' => 5,  'is_pusat' => 1, 'is_stakeholder' => 0],
            ['dept_id' => 6,  'dept_code' => '6',  'dept_name' => 'Layanan Pengadaan',   'isaktif' => 1, 'urut' => 6,  'is_pusat' => 1, 'is_stakeholder' => 0],
            ['dept_id' => 7,  'dept_code' => '7',  'dept_name' => 'MIS',                 'isaktif' => 1, 'urut' => 7,  'is_pusat' => 1, 'is_stakeholder' => 0],
            ['dept_id' => 8,  'dept_code' => '8',  'dept_name' => 'Pelaksana Kegiatan',  'isaktif' => 1, 'urut' => 8,  'is_pusat' => 1, 'is_stakeholder' => 0],
            ['dept_id' => 9,  'dept_code' => '9',  'dept_name' => 'Pemasaran & Humas',   'isaktif' => 1, 'urut' => 9,  'is_pusat' => 1, 'is_stakeholder' => 0],
            ['dept_id' => 10, 'dept_code' => '10', 'dept_name' => 'Penjaminan Mutu & K3', 'isaktif' => 1, 'urut' => 10, 'is_pusat' => 1, 'is_stakeholder' => 0],
            ['dept_id' => 11, 'dept_code' => '11', 'dept_name' => 'Rentek',               'isaktif' => 1, 'urut' => 11, 'is_pusat' => 1, 'is_stakeholder' => 0],
            ['dept_id' => 12, 'dept_code' => '12', 'dept_name' => 'Produksi',             'isaktif' => 1, 'urut' => 12, 'is_pusat' => 1, 'is_stakeholder' => 0],
            ['dept_id' => 13, 'dept_code' => '13', 'dept_name' => 'Renbang',              'isaktif' => 1, 'urut' => 13, 'is_pusat' => 1, 'is_stakeholder' => 0],
            ['dept_id' => 14, 'dept_code' => '14', 'dept_name' => 'SDM',                  'isaktif' => 1, 'urut' => 14, 'is_pusat' => 1, 'is_stakeholder' => 0],
            ['dept_id' => 15, 'dept_code' => '15', 'dept_name' => 'Sekretariat Perusahaan', 'isaktif' => 1, 'urut' => 15, 'is_pusat' => 1, 'is_stakeholder' => 0],
            ['dept_id' => 16, 'dept_code' => '16', 'dept_name' => 'SPI',                  'isaktif' => 1, 'urut' => 16, 'is_pusat' => 1, 'is_stakeholder' => 0],
            ['dept_id' => 17, 'dept_code' => '17', 'dept_name' => 'Umum',                 'isaktif' => 1, 'urut' => 17, 'is_pusat' => 1, 'is_stakeholder' => 0],
        ];

        // Sesuaikan nama tabel 'department' dengan nama tabel di database kamu
        DB::table('department')->insert($departments);
    }
}