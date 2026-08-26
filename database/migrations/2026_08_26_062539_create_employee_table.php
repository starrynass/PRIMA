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
        Schema::create('employee', function (Blueprint $table) {
        // Primary Key & Info Dasar
        $table->increments('pgw_id'); // INT AUTO_INCREMENT
        $table->string('pin', 10)->nullable();
        $table->string('id_usermachine', 25)->nullable();
        $table->string('nup', 10)->nullable();
        $table->string('nama', 50);

        // Data Pribadi
        $table->string('sex', 2)->nullable()->comment('0=perempuan, 1=laki-laki');
        $table->string('tempat', 50)->nullable();
        $table->date('tgl_lahir')->nullable();
        $table->date('tgl_masuk')->nullable();
        $table->date('tgl_habiskontrak')->nullable();
        $table->text('alamat')->nullable();
        $table->string('nohp', 15)->nullable();
        $table->string('gol_darah', 2)->nullable();
        $table->integer('is_nikah')->nullable()->comment('0=belum menikah, 1=menikah');
        $table->string('stts_keluarga', 4)->nullable();
        $table->integer('stts_kerja')->nullable();
        $table->integer('jml_anak')->nullable();

        // Relasi / Foreign Keys (Tipe data disesuaikan presisi)
        $table->smallInteger('agm_id')->unsigned()->nullable()->comment('ref_agama');
        $table->unsignedSmallInteger('off_id')->nullable()->comment('Relasi ke tabel office (SMALLINT)');
        $table->unsignedInteger('dept_id')->nullable()->comment('Relasi ke tabel department (INT)');
        $table->unsignedSmallInteger('subdept_id')->nullable()->comment('Relasi ke tabel department_sub (SMALLINT)');
        $table->smallInteger('occ_id')->unsigned()->nullable()->comment('occupation');
        $table->smallInteger('gol_id')->unsigned()->nullable()->comment('ref_golongan');
        $table->integer('pnd_id')->unsigned()->nullable()->comment('ref_pendidikan');
        $table->integer('pnd_id_awal')->nullable();
        $table->string('jurusan_pendidikan', 100)->nullable();
        $table->smallInteger('thn_lulus')->nullable();
        $table->integer('emp_group')->nullable()->comment('absen_group');

        // Berkas & Rekening
        $table->string('no_npwp', 100)->nullable();
        $table->string('no_rek', 50)->nullable();
        $table->string('bank_rek', 50)->nullable();
        $table->smallInteger('last_mkg_m')->nullable();
        $table->smallInteger('last_mkg_y')->nullable();
        $table->smallInteger('last_mkk_m')->nullable();
        $table->smallInteger('last_mkk_y')->nullable();

        // Status & Gaji
        $table->tinyInteger('is_bpjskes')->nullable();
        $table->string('status_ptkp', 255)->nullable();
        $table->string('kode_bagian_gaji', 50)->nullable();
        $table->string('kode_satker', 10)->nullable();
        $table->smallInteger('is_hitung_jp')->nullable();
        $table->smallInteger('is_aktif_gaji')->nullable()->comment('1=Aktif, 0=Nonaktif');

        // Profil & Aplikasi Mobile
        $table->string('file_ktp', 255)->nullable();
        $table->string('foto_pegawai', 255)->nullable();
        $table->string('foto_profil_hp', 255)->nullable();
        $table->string('username_mobile', 50)->nullable();
        $table->string('password_mobile', 100)->nullable();
        $table->tinyInteger('is_absenluar')->nullable()->comment('1=Boleh, 0=Tidak');
        $table->string('device_token', 255)->nullable();
        $table->dateTime('dt_device_token')->nullable();
        $table->integer('update_change')->nullable();
        $table->integer('id_user')->nullable();

        // Flag Status Sistem
        $table->tinyInteger('is_pensiun')->nullable()->comment('1=Pensiun, 0=Aktif');
        $table->tinyInteger('is_hapus')->nullable();
        $table->smallInteger('is_absen_webview')->nullable();
        $table->string('app_version', 10)->nullable();

        $table->timestamps();

        // Deklarasi Constraints Foreign Key Fizik
        $table->foreign('off_id')->references('off_id')->on('office')->nullOnDelete();
        $table->foreign('dept_id')->references('dept_id')->on('department')->nullOnDelete();
        $table->foreign('subdept_id')->references('subdept_id')->on('department_sub')->nullOnDelete();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee');
    }
};
