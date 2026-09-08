<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

   public function run(): void
    {
        $this->call([
            OfficeSeeder::class,
            DepartmentSeeder::class,
            SubDepartmentSeeder::class,
            OccupationSeeder::class,
            EmployeeSeeder::class,  
        ]);
    }
}
