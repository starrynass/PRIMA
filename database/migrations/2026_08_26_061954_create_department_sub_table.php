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
        Schema::create('department_sub', function (Blueprint $table) {
            // Primary Key (smallint auto increment)
            $table->smallIncrements('subdept_id');

            // Informasi Sub Department
            $table->string('subdept_code', 20)->comment('Kode sub department');
            $table->string('subdept_name', 50)->comment('Nama sub department');

            // Foreign Key ke tabel department (INT)
            $table->unsignedInteger('dept_id')->comment('Relasi ke table department (FK)');
            
            // Status Keaktifan & Nama Tambahan
            $table->tinyInteger('isaktif')->default(1)->comment('Menandakan apakah sub department aktif atau tidak. 1 atau 0');
            $table->string('dept_name1', 255)->nullable();

            // Estimasi Gaji & Tunjangan
            $table->string('perkiraan_gaji', 15)->nullable()->comment('Estimasi atau nominal standar gaji sub-departemen');
            $table->string('perkiraan_tunjangan', 15)->nullable()->comment('Estimasi atau nominal standar tunjangan sub-departemen');
            $table->tinyInteger('jenis_adm_tek')->nullable();

            $table->timestamps();

            // Deklarasi Relasi Foreign Key
            $table->foreign('dept_id')
                  ->references('dept_id')
                  ->on('department')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('department_sub');
    }
};
