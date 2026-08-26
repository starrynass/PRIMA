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
        Schema::create('department', function (Blueprint $table) {
            $table->increments('dept_id');
            $table->string('dept_code', 10);
            $table->string('dept_name', 50);
            
            // Status & Urutan
            $table->boolean('isaktif')->default(1)->comment('Status keaktifan departemen (1 = Aktif, 0 = Nonaktif)');
            $table->integer('urut')->nullable()->comment('Urutan departemen');
            $table->integer('is_pusat')->default(0)->comment('Menandakan apakah kantor pusat (1 = Ya, 0 = Tidak)');

            // Urutan Laporan & Flag Tambahan
            $table->tinyInteger('urut_laporan')->nullable();
            $table->tinyInteger('urut_bulanan_laporan')->nullable();
            $table->tinyInteger('is_skateholder')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('department');
    }
};
