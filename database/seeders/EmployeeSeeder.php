<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

class EmployeeSeeder extends Seeder
{
    private const UNIT_ASSIGNMENTS = [
        'UK-CAB-BMD' => [3, 2, 8],
        'UK-CAB-CBN' => [5, 2, 8],
        'UK-CAB-CLG' => [6, 2, 8],
        'UK-CAB-CMS' => [7, 2, 8],
        'UK-CAB-CWI' => [4, 2, 8],
        'UK-CAB-JGL' => [8, 2, 8],
        'UK-CAB-KMG' => [9, 2, 8],
        'UK-CAB-LWL' => [10, 2, 8],
        'UK-CAB-PPJ' => [11, 2, 8],
        'UK-DIST-NRW' => [1, 3, 10],
        'UK-INS-CBN' => [24, 4, 17],
        'UK-INS-CCB' => [26, 4, 17],
        'UK-INS-CCBBV' => [20, 4, 17],
        'UK-INS-GP' => [31, 4, 17],
        'UK-INS-JC' => [23, 4, 17],
        'UK-INS-KK' => [34, 4, 17],
        'UK-INS-KWBG' => [35, 4, 17],
        'UK-INS-LC' => [38, 4, 17],
        'UK-INS-PPT' => [40, 4, 17],
        'UK-INS-SCBM' => [47, 4, 17],
        'UK-INS-THR' => [49, 4, 17],
        'UK-KEU' => [1, 5, 19],
        'UK-LP' => [1, 6, 23],
        'UK-MARHUM' => [1, 9, 33],
        'UK-MIS' => [1, 7, 26],
        'UK-PELKEG' => [1, 8, 31],
        'UK-PET' => [1, 11, 11],
        'UK-PMK3' => [1, 10, 48],
        'UK-PROD' => [1, 12, 50],
        'UK-RENBANG' => [1, 13, 52],
        'UK-SDM' => [1, 14, 54],
        'UK-SEKPER' => [1, 15, 55],
        'UK-SPI' => [1, 16, 57],
        'UK-UMUM' => [1, 17, 62],
    ];

    private const MALE_FIRST_NAMES = [
        'Aditya', 'Agung', 'Ahmad', 'Arif', 'Bagas', 'Bayu', 'Bima', 'Dedi',
        'Dimas', 'Eko', 'Fajar', 'Farhan', 'Fikri', 'Firman', 'Galih', 'Hendra',
        'Ilham', 'Indra', 'Joko', 'Lukman', 'Muhammad', 'Nanda', 'Rafi', 'Raka',
        'Rama', 'Rangga', 'Reza', 'Rizky', 'Satria', 'Taufik', 'Tegar', 'Teguh',
        'Wahyu', 'Yudha', 'Abimanyu', 'Abizar', 'Adnan', 'Adrian', 'Alif', 'Alvin',
        'Ananda', 'Andika', 'Angga', 'Anugrah', 'Ardi', 'Arga', 'Arya', 'Athan',
        'Bastian', 'Bintang', 'Daffa', 'Damar', 'Danang', 'Danu', 'Darmawan',
        'Dava', 'Dhani', 'Dicky', 'Doni', 'Fauzan', 'Ferdian', 'Gilang', 'Guntur',
        'Hafiz', 'Haikal', 'Imam', 'Iqbal', 'Iskandar', 'Jefri', 'Kamal', 'Kevin',
        'Miko', 'Nathan', 'Rafiq', 'Rasyid', 'Rendra', 'Rifqi', 'Rivan',
        'Rizal', 'Robby', 'Sandi', 'Surya', 'Syahrul', 'Tama', 'Tio', 'Umar',
        'Vino', 'Wisnu', 'Yogi', 'Yusuf', 'Zainal', 'Zaki', 'Adit', 'Akbar',
        'Andra', 'Arman', 'Bram', 'Candra', 'Dion', 'Fauzi', 'Haris',
        'Irwan', 'Rizwan', 'Zulfikar', 'Banyu', 'Cakra', 'Darma',
    ];

    private const FEMALE_FIRST_NAMES = [
        'Aisyah', 'Alya', 'Amalia', 'Anisa', 'Ayu', 'Citra', 'Dewi', 'Dinda',
        'Fitri', 'Hana', 'Indah', 'Intan', 'Kartika', 'Laras', 'Lestari', 'Maharani',
        'Melati', 'Nabila', 'Nadia', 'Nia', 'Nirmala', 'Puspita', 'Putri', 'Rahma',
        'Rani', 'Ratna', 'Salsabila', 'Sekar', 'Shinta', 'Tiara', 'Vania', 'Wulan',
        'Yunita', 'Zahra', 'Adelia', 'Adinda', 'Adira', 'Afifah', 'Ainun',
        'Amara', 'Amira', 'Anindya', 'Anjani', 'Annisa', 'Aprilia', 'Asri', 'Atika',
        'Aulia', 'Azizah', 'Bella', 'Cahya', 'Cempaka', 'Dahlia', 'Dara', 'Dian',
        'Diah', 'Dilla', 'Elisa', 'Farah', 'Fathia', 'Febriani', 'Fitria', 'Gita',
        'Hasna', 'Ika', 'Intania', 'Isyana', 'Jihan', 'Kania', 'Karina', 'Kirana',
        'Laila', 'Larasati', 'Maya', 'Meilani', 'Melinda', 'Meylani', 'Mira',
        'Nadira', 'Nayla', 'Nisa', 'Pertiwi', 'Pramesti', 'Rahmawati', 'Raisa',
        'Rosalina', 'Safira', 'Salsya', 'Sarah', 'Sari', 'Savira', 'Silvia',
        'Siti', 'Sofia', 'Suci', 'Syifa', 'Tasya', 'Utami', 'Widya', 'Yasmin',
        'Yuliana', 'Zahira', 'Zakia', 'Zalfa', 'Almira', 'Bening', 'Calista',
    ];

    private const BIRTH_CITIES = [
        'JAKARTA', 'BOGOR', 'DEPOK', 'BEKASI', 'BANDUNG', 'CIREBON', 'SUKABUMI',
        'TASIKMALAYA', 'GARUT', 'PURWAKARTA', 'SEMARANG', 'SURAKARTA', 'MAGELANG',
        'SALATIGA', 'PEKALONGAN', 'TEGAL', 'YOGYAKARTA', 'SLEMAN', 'BANTUL',
        'KULON PROGO', 'SURABAYA', 'MALANG', 'KEDIRI', 'MADIUN', 'BLITAR',
        'MOJOKERTO', 'PASURUAN', 'PROBOLINGGO', 'BANYUWANGI', 'SERANG', 'CILEGON',
        'TANGERANG', 'PANDEGLANG', 'BANJAR',
    ];

    private string $mobilePassword;

    public function run(): void
    {
        $units = DB::table('unit_kerja')
            ->orderBy('kode_unit_kerja')
            ->get(['kode_unit_kerja', 'nama_unit_kerja']);

        if ($units->count() !== count(self::UNIT_ASSIGNMENTS)) {
            throw new RuntimeException('EmployeeSeeder membutuhkan tepat 34 unit kerja.');
        }

        $this->validateMasterAssignments();
        $this->mobilePassword = Hash::make('password123');

        $roleSlots = [
            ['occupation_id' => 1],
            ['occupation_id' => 5],
            ['occupation_id' => 6],
            ['occupation_id' => 9],
            ['occupation_id' => 9],
            ['occupation_id' => 9],
        ];

        $id = 1;
        foreach ($units as $unit) {
            $assignment = self::UNIT_ASSIGNMENTS[$unit->kode_unit_kerja] ?? null;
            if (!$assignment) {
                throw new RuntimeException('Unit kerja belum memiliki pemetaan departemen: ' . $unit->kode_unit_kerja);
            }

            foreach ($roleSlots as $slot) {
                $employee = $this->employeeData(
                    $id,
                    $this->nameFor($id),
                    $slot['occupation_id'],
                    $unit,
                    $assignment,
                    0
                );
                DB::table('employee')->updateOrInsert(['pgw_id' => $id], $employee);
                $id++;
            }
        }

        foreach ($units->take(10)->values() as $index => $unit) {
            $assignment = self::UNIT_ASSIGNMENTS[$unit->kode_unit_kerja];
            $id = 205 + $index;
            $employee = $this->employeeData(
                $id,
                $this->nameFor($id),
                9,
                $unit,
                $assignment,
                1
            );
            DB::table('employee')->updateOrInsert(['pgw_id' => $id], $employee);
        }
    }

    private function validateMasterAssignments(): void
    {
        foreach (self::UNIT_ASSIGNMENTS as [$officeId, $departmentId, $subdepartmentId]) {
            if (!DB::table('office')->where('off_id', $officeId)->exists()
                || !DB::table('department')->where('dept_id', $departmentId)->exists()
                || !DB::table('department_sub')
                    ->where('subdept_id', $subdepartmentId)
                    ->where('dept_id', $departmentId)
                    ->exists()) {
                throw new RuntimeException('Data office, department, dan subdepartment harus di-seed sebelum employee.');
            }
        }
    }

    private function nameFor(int $id): string
    {
        $index = intdiv($id - 1, 2);
        $firstNames = $id % 2 === 0 ? self::FEMALE_FIRST_NAMES : self::MALE_FIRST_NAMES;
        if ($index >= count($firstNames)) {
            throw new RuntimeException('EmployeeSeeder belum memiliki cukup nama unik untuk gender pegawai.');
        }

        $firstName = $firstNames[$index % count($firstNames)];
        $allNames = array_merge(self::MALE_FIRST_NAMES, self::FEMALE_FIRST_NAMES);
        $familyName = $allNames[($id - 1 + 73) % count($allNames)];

        return $firstName . ' ' . $familyName;
    }

    private function birthCityFor(int $id): string
    {
        $cityIndex = ($id * 13 + 7) % count(self::BIRTH_CITIES);

        return self::BIRTH_CITIES[$cityIndex];
    }

    private function employeeData(
        int $id,
        string $name,
        int $occupationId,
        object $unit,
        array $assignment,
        int $isDeleted
    ): array {
        [$officeId, $departmentId, $subdepartmentId] = $assignment;
        $yearOfBirth = match ($occupationId) {
            1 => 1975 + ($id % 8),
            5 => 1980 + ($id % 8),
            6 => 1985 + ($id % 8),
            default => 1990 + ($id % 8),
        };
        $unitName = preg_replace('/^Unit Kerja\\s*/', '', $unit->nama_unit_kerja);

        return [
            'pgw_id' => $id,
            'nup' => '2026' . str_pad((string) $id, 6, '0', STR_PAD_LEFT),
            'nama' => $name,
            'sex' => $id % 2 === 0 ? 'P' : 'L',
            'tempat' => $this->birthCityFor($id),
            'tgl_lahir' => sprintf('%04d-%02d-%02d', $yearOfBirth, ($id % 12) + 1, ($id % 27) + 1),
            'tgl_masuk' => sprintf('%04d-01-01', $yearOfBirth + 25),
            'alamat' => sprintf('Jl. Raya %s No. %d, Kabupaten Bogor', $unitName, ($id % 99) + 1),
            'nohp' => '0812' . str_pad((string) $id, 8, '0', STR_PAD_LEFT),
            'off_id' => $officeId,
            'dept_id' => $departmentId,
            'subdept_id' => $subdepartmentId,
            'occ_id' => $occupationId,
            'kode_unit_kerja' => $unit->kode_unit_kerja,
            'jurusan_pendidikan' => null,
            'thn_lulus' => $yearOfBirth + 22,
            'no_npwp' => null,
            'foto_pegawai' => 'default.jpg',
            'username_mobile' => 'pegawai' . $id,
            'password_mobile' => $this->mobilePassword,
            'is_pensiun' => 0,
            'is_hapus' => $isDeleted,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
