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
        Schema::create('dp3_trans_periode_penilaian', function (Blueprint $table) {
            $table->string('periode_id', 20)->primary()->comment('ID unik periode penilaian (Primary Key)');
            $table->integer('bulan')->nullable();
            $table->integer('tahun')->nullable();
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_deadline')->nullable();
            $table->string('status', 30)->nullable();
            $table->string('dibuka_oleh_admin', 10)->nullable();
            $table->timestamps(); // Mengoverse created_at dan updated_at
            $table->string('created_by', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dp3_trans_periode_penilaian');
    }
};
