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
        Schema::create('dp3_master_pertanyaan_penilaian', function (Blueprint $table) {
            // Primary Key (String / Varchar)
            $table->string('pertanyaan_id', 20)->primary()->comment('ID unik pertanyaan (Primary Key)');

            // Foreign Key ke tabel dp3_master_kategori_penilaian
            $table->string('kategori_id', 20)->comment('Relasi ke table dp3_master_kategori_penilaian (FK)');

            // Isi Pertanyaan & Deskripsi
            $table->string('pertanyaan', 255)->comment('Pertanyaan untuk penilaian');
            $table->string('deskripsi', 255)->nullable()->comment('Penjelasan detail pertanyaan');
            $table->double('bobot_persen')->comment('Bobot persentase pertanyaan (%)');
            
            // Pengaturan Tampilan & Tipe
            $table->integer('urutan')->nullable()->comment('Urutan nomor pertanyaan');
            $table->string('jenis')->nullable()->comment('Tipe/jenis pertanyaan'); // Bisa diganti $table->enum('jenis', ['...']) jika sudah ada opsi spesifik
            $table->tinyInteger('status_aktif')->default(1)->comment('Status keaktifan pertanyaan (1 = Aktif, 0 = Nonaktif)');

            // Metadata Audit
            $table->string('created_by', 255)->nullable()->comment('Pembuat data (username/ID)');
            $table->timestamps(); // Mengcover created_at dan updated_at

            // Deklarasi Foreign Key Relasi
            $table->foreign('kategori_id')
                  ->references('kategori_id')
                  ->on('dp3_master_kategori_penilaian')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dp3_master_pertanyaan_penilaian');
    }
};
