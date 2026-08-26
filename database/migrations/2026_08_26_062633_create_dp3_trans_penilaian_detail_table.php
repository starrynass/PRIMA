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
        Schema::create('dp3_trans_penilaian_detail', function (Blueprint $table) {
            // Primary Key & Foreign Key Parent
            $table->id('penilaian_detail_id')->comment('ID unik penilaian detail (Primary Key)');
            $table->unsignedBigInteger('penilaian_id')->comment('Relasi ke table dp3_trans_penilaian (FK)');

            // Snapshot Pertanyaan Penilaian
            $table->string('pertanyaan_id', 20)->nullable()->comment('Relasi ke table dp3_master_pertanyaan_penilaian (FK)');
            $table->string('pertanyaan', 255)->nullable()->comment('Teks pertanyaan penilaian');
            $table->string('deskripsi', 255)->nullable()->comment('Penjelasan pertanyaan');
            $table->double('bobot_pertanyaan_persen')->nullable()->comment('Bobot persentase pertanyaan (%)');

            // Snapshot Skala & Nilai Awal (Penilai)
            $table->string('skala_id', 20)->nullable()->comment('Relasi ke table dp3_master_skala_nilai (FK)');
            $table->string('kode_nilai', 255)->nullable()->comment('Kode nilai');
            $table->string('nama_nilai', 255)->nullable()->comment('Label/nama nilai (misal: Baik, Sangat Baik)');
            $table->double('nilai_angka')->nullable()->comment('Konversi nilai angka dari penilai');
            $table->double('nilai_akhir')->nullable()->comment('Hasil kalkulasi nilai akhir');
            $table->string('catatan', 255)->nullable()->comment('Catatan penilai untuk butir pertanyaan ini');

            // Data Verifikasi
            $table->string('verifikasi_kode', 255)->nullable();
            $table->integer('verifikator_id')->nullable();
            $table->string('verif_kode_nilai', 10)->nullable();
            $table->string('verif_nama_nilai', 100)->nullable();
            $table->double('verif_nilai_angka')->nullable()->comment('Konversi nilai angka setelah diverifikasi');
            $table->double('verif_nilai_akhir')->nullable()->comment('Kalkulasi nilai akhir setelah diverifikasi');
            $table->string('verif_catatan', 255)->nullable()->comment('Catatan koreksi dari verifikator');
            $table->string('verif_nilai_sebelumnya', 255)->nullable()->comment('Catatan/rekam nilai sebelum dilakukan koreksi');
            $table->string('verif_alasan_koreksi', 255)->nullable()->comment('Alasan perubahan/koreksi nilai oleh verifikator');
            $table->string('verif_status', 255)->nullable()->comment('Status hasil verifikasi');
            $table->dateTime('verif_tanggal')->nullable()->comment('Tanggal dan waktu proses verifikasi dilakukan');
            $table->string('catatan_verifikasi', 255)->nullable()->comment('Catatan tambahan verifikasi');

            // Metadata Audit
            $table->string('created_by', 255)->nullable()->comment('Pembuat data (username/ID)');
            $table->string('updated_by', 255)->nullable()->comment('Pengubah data terakhir (username/ID)');
            $table->timestamps(); // Mengoverse created_at dan updated_at

            // Constraints Foreign Key
            $table->foreign('penilaian_id')
                  ->references('penilaian_id')
                  ->on('dp3_trans_penilaian')
                  ->onDelete('cascade');

            $table->foreign('pertanyaan_id')
                  ->references('pertanyaan_id')
                  ->on('dp3_master_pertanyaan_penilaian')
                  ->nullOnDelete();

            $table->foreign('skala_id')
                  ->references('skala_id')
                  ->on('dp3_master_skala_nilai')
                  ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dp3_trans_penilaian_detail');
    }
};