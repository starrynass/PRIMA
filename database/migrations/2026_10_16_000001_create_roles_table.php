<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name')->unique();
            $table->timestamps();
        });

        DB::table('roles')->insert([
            ['slug' => 'superadmin', 'name' => 'Super Admin', 'created_at' => now(), 'updated_at' => now()],
            ['slug' => 'direktur', 'name' => 'Direktur', 'created_at' => now(), 'updated_at' => now()],
            ['slug' => 'manager', 'name' => 'Manager', 'created_at' => now(), 'updated_at' => now()],
            ['slug' => 'staff', 'name' => 'Staff', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
