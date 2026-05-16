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
        Schema::create('data_beras', function (Blueprint $table) {
            $table->id();
            $table->year('tahun')->unique();
            $table->decimal('produksi_ton', 15, 2);
            $table->decimal('stok_awal_ton', 15, 2)->nullable();
            $table->decimal('impor_ton', 15, 2)->default(0);
            $table->decimal('ekspor_ton', 15, 2)->default(0);
            $table->decimal('konsumsi_ton', 15, 2);
            $table->decimal('ketersediaan_ton', 15, 2);
            $table->string('sumber_data')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_beras');
    }
};
