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
            // Primary Key (String / Varchar)
            $table->string('template_id', 20)->primary()->comment('ID unik template penilaian (Primary Key)');

            // Informasi Template
            $table->string('nama_template', 255)->comment('Nama template penilaian');

            // Metadata Audit
            $table->string('created_by', 225)->nullable()->comment('Pembuat data (username/ID)');
            $table->timestamps(); // Mengcover created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dp3_master_template_penilaian');
    }
};
