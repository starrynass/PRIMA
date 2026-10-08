<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employee', function (Blueprint $table) {
            // 1. Tambahkan kolomnya secara nullable terlebih dahulu agar data lama tidak error
            $table->string('kode_unit_kerja', 20)->nullable()->after('occ_id');

            // 2. Tambahkan Foreign Key yang mengacu ke tabel unit_kerja
            $table->foreign('kode_unit_kerja')
                  ->references('kode_unit_kerja')
                  ->on('unit_kerja')
                  ->onUpdate('cascade')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('employee', function (Blueprint $table) {
            // Hapus foreign key dulu baru hapus kolomnya
            $table->dropForeign(['kode_unit_kerja']);
            $table->dropColumn('kode_unit_kerja');
        });
    }
};