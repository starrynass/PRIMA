<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dp3_trans_penilaian', function (Blueprint $table) {
            if (!Schema::hasColumn('dp3_trans_penilaian', 'status_verifikator')) {
                $table->string('status_verifikator', 50)->nullable();
            }
            if (!Schema::hasColumn('dp3_trans_penilaian', 'total_nilai_verifikator')) {
                $table->decimal('total_nilai_verifikator', 5, 2)->nullable();
            }
            if (!Schema::hasColumn('dp3_trans_penilaian', 'predikat_verifikator')) {
                $table->string('predikat_verifikator', 100)->nullable();
            }
            if (!Schema::hasColumn('dp3_trans_penilaian', 'catatan_verifikator')) {
                $table->text('catatan_verifikator')->nullable();
            }
            if (!Schema::hasColumn('dp3_trans_penilaian', 'verifikator_id')) {
                $table->unsignedBigInteger('verifikator_id')->nullable();
            }
            if (!Schema::hasColumn('dp3_trans_penilaian', 'tanggal_verifikasi')) {
                $table->dateTime('tanggal_verifikasi')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('dp3_trans_penilaian', function (Blueprint $table) {
            $columns = array_filter([
                'status_verifikator',
                'total_nilai_verifikator',
                'predikat_verifikator',
                'catatan_verifikator',
                'verifikator_id',
                'tanggal_verifikasi',
            ], fn($col) => Schema::hasColumn('dp3_trans_penilaian', $col));

            if (!empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};