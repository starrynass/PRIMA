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
        Schema::create('occupation', function (Blueprint $table) {
            $table->smallIncrements('occ_id')->comment('ID unik occupation (Primary Key)');
            $table->string('occ_name', 50)->comment('Nama jabatan / occupation');
            $table->string('occ_alias', 50)->nullable()->comment('Nama alias jabatan');
            $table->text('sub_off_id')->nullable();
            $table->integer('urut')->nullable();
            $table->string('ket', 255)->nullable()->comment('Keterangan tambahan mengenai jabatan');
            $table->tinyInteger('is_pejabat')->default(0)->comment('Menandakan apakah termasuk struktur pejabat (1 = Ya, 0 = Tidak)');
            $table->smallInteger('is_aktif')->default(1)->comment('Status keaktifan jabatan (1 = Aktif, 0 = Nonaktif)');
            $table->smallInteger('level')->nullable();
            $table->timestamp('created_at')->nullable()->comment('Waktu data dibuat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('occupation');
    }
};
