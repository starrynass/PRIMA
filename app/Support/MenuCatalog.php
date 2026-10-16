<?php

namespace App\Support;

class MenuCatalog
{
    public static function all(): array
    {
        return [
            'dashboard' => [
                'label' => 'Dashboard',
                'url' => 'Dashboard',
                'route' => 'dashboard.penilaian',
                'icon' => 'fa-house',
                'children' => [],
            ],
            'master' => [
                'label' => 'Master',
                'url' => 'Master',
                'icon' => 'fa-pen-to-square',
                'children' => [
                    [
                        'key' => 'master.skala-predikat',
                        'label' => 'Skala & Predikat Nilai',
                        'url' => 'Master/SkalaPredikatNilai',
                        'route' => 'skala-predikat.index',
                    ],
                    [
                        'key' => 'master.template-penilaian',
                        'label' => 'Template, Fase dan Pertanyaan',
                        'url' => 'Master/TemplateFaseDanPertanyaan',
                        'route' => 'template-penilaian.index',
                    ],
                ],
            ],
            'penilaian' => [
                'label' => 'Penilaian',
                'url' => 'Penilaian',
                'icon' => 'fa-users',
                'children' => [
                    [
                        'key' => 'penilaian.periode',
                        'label' => 'Periode Penilaian',
                        'url' => 'Penilaian/PeriodePenilaian',
                        'route' => 'periode-penilaian.index',
                    ],
                    [
                        'key' => 'penilaian.kelola',
                        'label' => 'Kelola Penilaian',
                        'url' => 'Penilaian/KelolaPenilaian',
                        'route' => 'kelola-penilaian.index',
                    ],
                    [
                        'key' => 'penilaian.verifikasi',
                        'label' => 'Verifikasi Penilaian',
                        'url' => 'Penilaian/VerifikasiPenilaian',
                        'route' => 'verifikasi-penilaian.index',
                    ],
                    [
                        'key' => 'penilaian.catatan',
                        'label' => 'Catatan Penilaian',
                        'url' => 'Penilaian/CatatanPenilaian',
                        'route' => 'catatan-penilaian.index',
                    ],
                ],
            ],
            'laporan' => [
                'label' => 'Laporan',
                'url' => 'Laporan',
                'icon' => 'fa-file-lines',
                'children' => [
                    [
                        'key' => 'laporan.tahunan',
                        'label' => 'Laporan Tahunan',
                        'url' => 'Laporan/Tahunan',
                        'route' => 'laporan.tahunan.index',
                    ],
                    [
                        'key' => 'laporan.rekap-nilai',
                        'label' => 'Laporan Rekap Nilai',
                        'url' => 'Laporan/RekapNilai',
                        'route' => 'laporan.rekap-nilai.index',
                    ],
                    [
                        'key' => 'laporan.indeks-unit-kerja',
                        'label' => 'Laporan Index Unit Kerja',
                        'url' => 'Laporan/IndeksUnitKerja',
                        'route' => 'laporan.indeks-unit-kerja.index',
                    ],
                    [
                        'key' => 'laporan.indeks-kompetensi',
                        'label' => 'Laporan Index Kompetensi',
                        'url' => 'Laporan/IndexKompetensi',
                        'route' => 'laporan.indeks-kompetensi.index',
                    ],
                ],
            ],
            'settings' => [
                'label' => 'Setting',
                'url' => 'Setting',
                'icon' => 'fa-gear',
                'children' => [
                    [
                        'key' => 'settings.hak-akses',
                        'label' => 'Hak Akses',
                        'url' => 'Setting/HakAkses',
                        'route' => 'setting.hak-akses',
                    ],
                    [
                        'key' => 'settings.otorisasi-menu',
                        'label' => 'Otorisasi Menu',
                        'url' => 'Setting/OtorisasiMenu',
                        'route' => 'setting.otorisasi-menu',
                    ],
                    [
                        'key' => 'settings.roles',
                        'label' => 'Kelola Role',
                        'url' => 'Setting/Role',
                        'route' => 'setting.roles',
                    ],
                ],
            ],
        ];
    }
}
