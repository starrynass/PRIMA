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
        Schema::create('dp3_master_template_penilaian', function (Blueprint $table) {

            $table->string('template_id', 20)->primary()->comment('ID unik template penilaian (Primary Key)');
            $table->string('nama_template', 255)->comment('Nama template penilaian');

            $table->string('occ_id', 255)->comment('Daftar ID Jabatan dipisah koma'); 

            $table->string('status_aktif', 20)->nullable();
            $table->string('created_by', 225)->nullable()->comment('Pembuat data (username/ID)');
            $table->timestamps(); 
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dp3_master_template_penilaian');
    }
};