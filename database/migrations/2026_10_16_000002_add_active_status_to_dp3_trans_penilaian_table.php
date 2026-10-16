<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dp3_trans_penilaian', function (Blueprint $table) {
            $table->boolean('is_active')->default(true);
            $table->string('inactive_reason', 255)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('dp3_trans_penilaian', function (Blueprint $table) {
            $table->dropColumn(['is_active', 'inactive_reason']);
        });
    }
};
