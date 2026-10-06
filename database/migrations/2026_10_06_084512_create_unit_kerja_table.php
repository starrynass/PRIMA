<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('unit_kerja', function (Blueprint $table) {
            $table->string('kode_unit_kerja')->primary(); // Atau $table->id() kalau pakai ID auto-increment
            $table->string('nama_unit_kerja');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('unit_kerja');
    }
};