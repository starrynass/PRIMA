<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dp3_trans_penilaian', function (Blueprint $table) {
            // 1. Tambah kolom pgw_kode_unit_kerja secara nullable
            $table->string('pgw_kode_unit_kerja', 20)->nullable()->after('pegawai_id');

            // 2. Foreign key mengacu ke kode_unit_kerja di tabel unit_kerja
            $table->foreign('pgw_kode_unit_kerja')
                  ->references('kode_unit_kerja')
                  ->on('unit_kerja')
                  ->onUpdate('cascade')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('dp3_trans_penilaian', function (Blueprint $table) {
            // Drop foreign key berdasarkan nama kolom baru
            $table->dropForeign(['pgw_kode_unit_kerja']);
            $table->dropColumn('pgw_kode_unit_kerja');
        });
    }
};