<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('data_beras', function (Blueprint $table) {
            $table->unsignedTinyInteger('bulan')->after('tahun');

            $table->dropUnique('data_beras_tahun_unique');

            $table->unique(['tahun', 'bulan']);
        });
    }

    public function down(): void
    {
        Schema::table('data_beras', function (Blueprint $table) {
            $table->dropUnique(['tahun', 'bulan']);
            $table->unique('tahun');
            $table->dropColumn('bulan');
        });
    }
};