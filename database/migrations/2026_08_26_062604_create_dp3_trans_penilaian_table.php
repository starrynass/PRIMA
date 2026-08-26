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
        Schema::create('dp3_trans_penilaian', function (Blueprint $table) {
            // Primary Key & Kode Transaksi
            $table->id('penilaian_id')->comment('ID unik penilaian (Primary Key)');
            $table->string('kode', 255)->comment('Kode unik transaksi penilaian');

            // Foreign Keys ke Master Penilaian & Pegawai
            $table->string('periode_id', 20)->comment('Relasi ke table periode / dp3_master_periode (FK)');
            $table->string('pegawai_id', 20)->comment('Relasi ke table pegawai (FK)');

            // Snapshot Data Pegawai Yang Dinilai
            $table->string('pgw_nup', 255)->nullable()->comment('NUP (Nomor Unik Pegawai) yang dinilai');
            $table->string('pgw_nama', 255)->nullable()->comment('Nama lengkap pegawai yang dinilai');
            $table->string('pgw_sex', 255)->nullable()->comment('Jenis kelamin pegawai');
            $table->integer('pgw_id_status_pegawai')->nullable();
            $table->string('pgw_stts_pgw', 100)->nullable();
            
            // Snapshot Jabatan & Struktur Organisasi
            $table->integer('pgw_id_jabatan')->nullable()->comment('Relasi ke table occupation (FK)');
            $table->string('pgw_kd_jabatan', 50)->nullable()->comment('Kode jabatan pegawai');
            $table->string('pgw_jabatan', 100)->nullable()->comment('Nama jabatan pegawai');
            $table->integer('pgw_id_dept')->nullable()->comment('Relasi ke table department (FK)');
            $table->string('pgw_dept_name', 100)->nullable()->comment('Nama departemen pegawai');
            $table->integer('pgw_id_subdept')->nullable()->comment('Relasi ke table department_sub (FK)');
            $table->string('pgw_id_subdept_name', 200)->nullable()->comment('Nama sub-departemen pegawai');
            $table->integer('pgw_off_id')->nullable()->comment('Relasi ke table office (FK)');
            $table->string('pgw_off_name', 200)->nullable()->comment('Nama kantor/cabang pegawai');
            $table->string('pgw_kode_satker', 255)->nullable()->comment('Kode satuan kerja pegawai');
            $table->integer('pgw_id_golongan')->nullable()->comment('Relasi ke table golongan (FK)');
            $table->string('pgw_golongan', 200)->nullable()->comment('Teks golongan/pangkat pegawai');
            $table->integer('pgw_mkg_y')->nullable();

            // Snapshot Template Penilaian
            $table->string('template_id', 20)->nullable()->comment('Relasi ke table dp3_master_template_penilaian (FK)');
            $table->string('template_nama', 255)->nullable()->comment('Nama template penilaian yang digunakan');

            // Data Penilai & Verifikator
            $table->string('penilai_id', 20)->nullable();
            $table->string('verifikator_id', 20)->nullable();
            $table->tinyInteger('penilai_manual')->default(0)->comment('Penanda opsi penilai manual (1 = Ya, 0 = Tidak)');

            // Hasil Penilaian & Verifikasi
            $table->double('total_nilai')->nullable()->comment('Total akumulasi nilai awal/penilai');
            $table->double('total_nilai_verifikator')->nullable()->comment('Total akumulasi nilai setelah verifikasi');
            $table->string('predikat_id', 20)->nullable()->comment('Relasi ke table dp3_master_predikat_nilai (FK)');
            $table->string('predikat', 255)->nullable()->comment('Teks predikat hasil nilai');
            $table->string('predikat_verifikator', 255)->nullable()->comment('Teks predikat hasil verifikasi');
            $table->string('kode_verifikasi', 255)->nullable();
            
            // Catatan Evaluasi
            $table->string('catatan_verifikator', 255)->nullable()->comment('Catatan evaluasi dari verifikator');
            $table->string('catatan', 255)->nullable()->comment('Catatan umum penilaian');

            // Status Process Transaksi
            $table->string('status_nilai', 50)->nullable(); // Menggunakan string fleksibel/enum
            $table->string('status_verifitor', 50)->nullable();

            // Tanggal Log Status
            $table->date('tanggal_submit')->nullable()->comment('Tanggal dokumen disubmit penilai');
            $table->date('tanggal_verifikasi')->nullable()->comment('Tanggal dokumen diverifikasi');
            $table->date('tanggal_revisi')->nullable()->comment('Tanggal permintaan revisi');

            // Metadata Audit
            $table->string('created_by', 255)->nullable()->comment('Pembuat data transaksi');
            $table->timestamps();

            // Deklarasi Foreign Key Constraints
            $table->foreign('template_id')
                  ->references('template_id')
                  ->on('dp3_master_template_penilaian')
                  ->nullOnDelete();

            $table->foreign('predikat_id')
                  ->references('predikat_id')
                  ->on('dp3_master_predikat_nilai')
                  ->nullOnDelete();

            $table->foreign('periode_id')
                ->references('periode_id')
                ->on('dp3_trans_periode_penilaian') // Sesuaikan nama tabel target
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dp3_trans_penilaian');
    }
};