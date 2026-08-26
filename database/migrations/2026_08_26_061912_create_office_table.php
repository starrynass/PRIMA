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
        Schema::create('office', function (Blueprint $table) {
            // Primary Key (smallint auto increment)
            $table->smallIncrements('off_id');

            // Informasi Kantor
            $table->char('off_code', 5)->comment('Kode unik kantor');
            $table->string('off_name', 50)->comment('Nama unit kantor / cabang');
            $table->string('off_telp', 25)->nullable()->comment('Nomor telepon kantor');
            $table->string('off_addr', 150)->nullable()->comment('Alamat lengkap kantor');
            $table->string('off_cp', 35)->nullable()->comment('Contact Person');

            // Tipe & Status
            $table->smallInteger('of_type')->nullable();
            $table->integer('online')->nullable();

            // Lokasi & Absensi (GPS Radius)
            $table->string('of_lat', 255)->nullable()->comment('Koordinat Latitude lokasi kantor');
            $table->string('of_long', 255)->nullable()->comment('Koordinat Longitude lokasi kantor');
            $table->integer('radius')->nullable()->comment('Jarak radius jangkauan / batas absensi (meter)');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('office');
    }
};
