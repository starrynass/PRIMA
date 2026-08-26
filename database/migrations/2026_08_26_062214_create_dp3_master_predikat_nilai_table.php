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
       Schema::create('dp3_master_predikat_nilai', function (Blueprint $table) {
            // Primary Key (String / Varchar)
            $table->string('predikat_id', 20)->primary()->comment('ID unik predikat nilai (Primary Key)');

            // Detail Predikat & Rentang Nilai
            $table->string('kode', 255)->comment('Kode predikat');
            $table->double('nilai_min')->comment('Batas nilai minimum');
            $table->double('nilai_max')->comment('Batas nilai maksimum');
            $table->string('predikat', 255)->comment('Sebutan predikat (misal: Sangat Baik, Baik, Cukup)');

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
        Schema::dropIfExists('dp3_master_predikat_nilai');
    }
};
