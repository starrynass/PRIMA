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
        Schema::create('dp3_master_kategori_penilaian', function (Blueprint $table) {
            // Primary Key (String / Varchar)
            $table->string('kategori_id', 20)->primary()->comment('ID unik kategori penilaian (Primary Key)');

            // Relasi ke tabel dp3_master_template_penilaian
            $table->string('template_id', 20)->nullable()->comment('Relasi ke table dp3_master_template_penilaian (FK)');

            // Informasi Kategori & Bobot
            $table->string('kode', 255)->comment('Kode kategori penilaian');
            $table->string('nama', 255)->comment('Nama kategori penilaian');
            $table->double('bobot_persen')->comment('Bobot persentase dari kategori penilaian (%)');
            $table->integer('urutan')->nullable()->comment('Urutan tampilan kategori');

            // Metadata Audit
            $table->string('created_by', 255)->nullable()->comment('Pembuat data (username/ID)');
            $table->string('updated_by', 255)->nullable()->comment('Pengubah data (username/ID)');
            $table->timestamps(); // Mengcover created_at dan updated_at

            // Deklarasi Foreign Key (Aktifkan jika tabel template_penilaian sudah dibuat lebih dulu)
            
            $table->foreign('template_id')
                  ->references('template_id')
                  ->on('dp3_master_template_penilaian')
                  ->onDelete('cascade');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dp3_master_kategori_penilaian');
    }
};
