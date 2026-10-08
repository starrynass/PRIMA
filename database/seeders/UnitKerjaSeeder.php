<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnitKerjaSeeder extends Seeder
{
    public function run(): void
    {
        $unitKerja = [
            // Gambar 1
            ['kode_unit_kerja' => 'UK-CAB-BMD', 'nama_unit_kerja' => 'Unit Kerja Cabang Babakan Madang'],
            ['kode_unit_kerja' => 'UK-CAB-CBN', 'nama_unit_kerja' => 'Unit Kerja Cabang Cibinong'],
            ['kode_unit_kerja' => 'UK-CAB-CLG', 'nama_unit_kerja' => 'Unit Kerja Cabang Cileungsi'],
            ['kode_unit_kerja' => 'UK-CAB-CMS', 'nama_unit_kerja' => 'Unit Kerja Cabang Ciomas'],
            ['kode_unit_kerja' => 'UK-CAB-CWI', 'nama_unit_kerja' => 'Unit Kerja Cabang Ciawi'],
            ['kode_unit_kerja' => 'UK-CAB-JGL', 'nama_unit_kerja' => 'Unit Kerja Cabang Jonggol'],
            ['kode_unit_kerja' => 'UK-CAB-KMG', 'nama_unit_kerja' => 'Unit Kerja Cabang Kemang'],
            ['kode_unit_kerja' => 'UK-CAB-LWL', 'nama_unit_kerja' => 'Unit Kerja Cabang Leuwiliang'],
            ['kode_unit_kerja' => 'UK-CAB-PPJ', 'nama_unit_kerja' => 'Unit Kerja Cabang Parung Panjang'],
            ['kode_unit_kerja' => 'UK-DIST-NRW', 'nama_unit_kerja' => 'Unit Kerja Distribusi dan NRW'],
            ['kode_unit_kerja' => 'UK-INS-CBN', 'nama_unit_kerja' => 'Unit Kerja Instalasi Cibinong'],
            ['kode_unit_kerja' => 'UK-INS-CCB', 'nama_unit_kerja' => 'Unit Kerja Instalasi Ciburial, Cikahuripan & Binong'],
            ['kode_unit_kerja' => 'UK-INS-CCBBV', 'nama_unit_kerja' => 'Unit Kerja Instalasi Cijeruk, Cibedug, BRR, Brujul & Vimalla Hills'],
            ['kode_unit_kerja' => 'UK-INS-GP', 'nama_unit_kerja' => 'Unit Kerja Instalasi Gunung Putri'],
            ['kode_unit_kerja' => 'UK-INS-JC', 'nama_unit_kerja' => 'Unit Kerja Instalasi Jonggol Cariu'],
            ['kode_unit_kerja' => 'UK-INS-KK', 'nama_unit_kerja' => 'Unit Kerja Instalasi Kedunghalang & Katulampa'],
            ['kode_unit_kerja' => 'UK-INS-KWBG', 'nama_unit_kerja' => 'Unit Kerja Instalasi Kota Wisata & Bukit Golf'],

            // Gambar 2
            ['kode_unit_kerja' => 'UK-INS-LC', 'nama_unit_kerja' => 'Unit Kerja Instalasi Leuwiliang & Cibungbulang'],
            ['kode_unit_kerja' => 'UK-INS-PPT', 'nama_unit_kerja' => 'Unit Kerja Instalasi Parung Panjang & Tenjo'],
            ['kode_unit_kerja' => 'UK-INS-SCBM', 'nama_unit_kerja' => 'Unit Kerja Instalasi Sukaraja, Citeureup & Babakan Madang'],
            ['kode_unit_kerja' => 'UK-INS-THR', 'nama_unit_kerja' => 'Unit Kerja Instalasi Tajur Halang & Rumpin'],
            ['kode_unit_kerja' => 'UK-KEU', 'nama_unit_kerja' => 'Unit Kerja Keuangan'],
            ['kode_unit_kerja' => 'UK-LP', 'nama_unit_kerja' => 'Unit Kerja Layanan Pengadaan'],
            ['kode_unit_kerja' => 'UK-MARHUM', 'nama_unit_kerja' => 'Unit Kerja Pemasaran dan Humas'],
            ['kode_unit_kerja' => 'UK-MIS', 'nama_unit_kerja' => 'Unit Kerja MIS'],
            ['kode_unit_kerja' => 'UK-PELKEG', 'nama_unit_kerja' => 'Unit Kerja Pelaksana Kegiatan'],
            ['kode_unit_kerja' => 'UK-PET', 'nama_unit_kerja' => 'Unit Kerja Perencanaan dan Evaluasi Teknik'],
            ['kode_unit_kerja' => 'UK-PMK3', 'nama_unit_kerja' => 'Unit Kerja Penjaminan Mutu dan K3'],
            ['kode_unit_kerja' => 'UK-PROD', 'nama_unit_kerja' => 'Unit Kerja Produksi'],
            ['kode_unit_kerja' => 'UK-RENBANG', 'nama_unit_kerja' => 'Unit Kerja Perencanaan dan Pengembangan'],
            ['kode_unit_kerja' => 'UK-SDM', 'nama_unit_kerja' => 'Unit Kerja Sumber Daya Manusia'],
            ['kode_unit_kerja' => 'UK-SEKPER', 'nama_unit_kerja' => 'Unit Kerja Sekretariat Perusahaan'],
            ['kode_unit_kerja' => 'UK-SPI', 'nama_unit_kerja' => 'Unit Kerja Satuan Pengawasan Intern'],
            ['kode_unit_kerja' => 'UK-UMUM', 'nama_unit_kerja' => 'Unit Kerja Umum'],
        ];

        DB::table('unit_kerja')->insert($unitKerja);
    }
}