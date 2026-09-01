<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OccupationSeeder extends Seeder
{
    public function run(): void
    {
        $occupation = [
            ['occ_id' => 1,  'occ_name' => 'Direktur Utama',                                        'occ_alias' => 'Dirut',     'sub_off_id' => null,                'urut' => 1,      'ket' => null, 'is_pejabat' => 1, 'is_aktif' => 1, 'created_at' => null, 'level' => 1],
            ['occ_id' => 2,  'occ_name' => 'Direktur Umum',                                         'occ_alias' => 'Dirum',     'sub_off_id' => null,                'urut' => 2,      'ket' => null, 'is_pejabat' => 1, 'is_aktif' => 1, 'created_at' => null, 'level' => 1],
            ['occ_id' => 3,  'occ_name' => 'Direktur Operasional',                                  'occ_alias' => 'Dirop',     'sub_off_id' => null,                'urut' => 3,      'ket' => null, 'is_pejabat' => 1, 'is_aktif' => 1, 'created_at' => null, 'level' => 1],
            ['occ_id' => 4,  'occ_name' => 'Sekretaris Perusahaan',                                 'occ_alias' => 'Sekper',    'sub_off_id' => null,                'urut' => 4,      'ket' => null, 'is_pejabat' => 0, 'is_aktif' => 1, 'created_at' => null, 'level' => 2],
            ['occ_id' => 5,  'occ_name' => 'Manajer',                                               'occ_alias' => 'Mgr',       'sub_off_id' => null,                'urut' => 5,      'ket' => null, 'is_pejabat' => 0, 'is_aktif' => 1, 'created_at' => null, 'level' => 2],
            ['occ_id' => 6,  'occ_name' => 'Asmen',                                                 'occ_alias' => 'As.Mgr',    'sub_off_id' => null,                'urut' => 999,    'ket' => null, 'is_pejabat' => 0, 'is_aktif' => 1, 'created_at' => null, 'level' => 3],
            ['occ_id' => 9,  'occ_name' => 'Staf',                                                  'occ_alias' => 'Staf',      'sub_off_id' => null,                'urut' => 100002, 'ket' => null, 'is_pejabat' => 0, 'is_aktif' => 1, 'created_at' => null, 'level' => 4],
            ['occ_id' => 10, 'occ_name' => 'Staf Instalasi',                                        'occ_alias' => 'Staf Inst', 'sub_off_id' => null,                'urut' => 10000,  'ket' => null, 'is_pejabat' => 0, 'is_aktif' => 1, 'created_at' => null, 'level' => 4],
            ['occ_id' => 14, 'occ_name' => 'As.Mgr.Instalasi Cijeruk, Cibedug, BRR, GSP, Bruju',    'occ_alias' => '',          'sub_off_id' => '67,64,20,66,21',    'urut' => 6,      'ket' => null, 'is_pejabat' => 0, 'is_aktif' => 1, 'created_at' => null, 'level' => 3],
            ['occ_id' => 17, 'occ_name' => 'As.Mgr.Instalasi Ciburial, Cikahuripan & Binong',       'occ_alias' => '-',         'sub_off_id' => '26,27,15',          'urut' => 7,      'ket' => null, 'is_pejabat' => 0, 'is_aktif' => 1, 'created_at' => null, 'level' => 3],
            ['occ_id' => 18, 'occ_name' => 'As.Mgr.Instalasi Tajur Halang & Rumpin',                'occ_alias' => '-',         'sub_off_id' => '49,45',             'urut' => 8,      'ket' => null, 'is_pejabat' => 0, 'is_aktif' => 1, 'created_at' => null, 'level' => 3],
            ['occ_id' => 19, 'occ_name' => 'As.Mgr.Instalasi Sukaraja, Res.A, Purbalingga, Cit',    'occ_alias' => '-',         'sub_off_id' => '47,41,42,28,13',    'urut' => 9,      'ket' => null, 'is_pejabat' => 0, 'is_aktif' => 1, 'created_at' => null, 'level' => 3],
            ['occ_id' => 20, 'occ_name' => 'As.Mgr.Instalasi Parung Panjang & Tenjo',               'occ_alias' => '-',         'sub_off_id' => '50,11',             'urut' => 10,     'ket' => null, 'is_pejabat' => 0, 'is_aktif' => 1, 'created_at' => null, 'level' => 3],
            ['occ_id' => 21, 'occ_name' => 'As.Mgr.Leuwiliang & Cibungbulang',                      'occ_alias' => '-',         'sub_off_id' => '38,25',             'urut' => 11,     'ket' => null, 'is_pejabat' => 0, 'is_aktif' => 1, 'created_at' => null, 'level' => 3],
            ['occ_id' => 22, 'occ_name' => 'As.Mgr.Instalasi Kota Wisata & Bukit Golf',             'occ_alias' => '-',         'sub_off_id' => '35,22',             'urut' => 12,     'ket' => null, 'is_pejabat' => 0, 'is_aktif' => 1, 'created_at' => null, 'level' => 3],
            ['occ_id' => 23, 'occ_name' => 'As.Mgr.Instalasi Kd.Halang & Katulampa',                'occ_alias' => '-',         'sub_off_id' => '34,33',             'urut' => 13,     'ket' => null, 'is_pejabat' => 0, 'is_aktif' => 1, 'created_at' => null, 'level' => 3],
            ['occ_id' => 26, 'occ_name' => 'As.Mgr.Instalasi Jonggol & Cariu',                      'occ_alias' => '-',         'sub_off_id' => '8,23',             'urut' => 14,     'ket' => null, 'is_pejabat' => 0, 'is_aktif' => 1,'created_at' => '2025-04-23 07:40:23', 'level' => 3],
        ];

        DB::table('occupation')->insert($occupation);
    }
}