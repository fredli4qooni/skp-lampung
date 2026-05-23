<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Perhatikan nama tabelnya sekarang persis sesuai database Anda (tanpa 's')
        Schema::table('hasil_prediksi', function (Blueprint $table) {
            // Kita pastikan kolomnya benar-benar belum ada sebelum ditambahkan
            if (!Schema::hasColumn('hasil_prediksi', 'bulan_prediksi')) {
                $table->unsignedTinyInteger('bulan_prediksi')->after('tahun_prediksi')->default(1);
            }
        });
    }

    public function down(): void
    {
        Schema::table('hasil_prediksi', function (Blueprint $table) {
            if (Schema::hasColumn('hasil_prediksi', 'bulan_prediksi')) {
                $table->dropColumn('bulan_prediksi');
            }
        });
    }
};