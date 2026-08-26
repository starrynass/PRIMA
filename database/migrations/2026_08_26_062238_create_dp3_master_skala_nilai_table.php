<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('dp3_master_skala_nilai', function (Blueprint $table) {
            // Primary Key (String / Varchar)
            $table->string('skala_id', 20)->primary()->comment('ID unik skala nilai (Primary Key)');

            // Detail Skala & Konversi Nilai
            $table->string('kode_nilai', 255)->comment('Kode skala nilai');
            $table->string('nama_nilai', 255)->comment('Label/nama nilai');
            $table->double('nilai_angka')->comment('Konversi nilai angka');
            $table->string('deskripsi', 255)->nullable();

            // Metadata Audit
            $table->string('created_by', 255)->nullable()->comment('Pembuat data (username/ID)');
            $table->timestamps(); // Mengcover created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_skala_nilai');
    }
};
