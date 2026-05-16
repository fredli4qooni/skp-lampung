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
        Schema::create('hasil_prediksi', function (Blueprint $table) {
            $table->id();
            $table->string('run_id', 36);
            $table->year('tahun_prediksi');
            $table->decimal('nilai_prediksi', 15, 2);
            $table->decimal('lower_bound', 15, 2)->nullable();
            $table->decimal('upper_bound', 15, 2)->nullable();
            $table->integer('parameter_p');
            $table->integer('parameter_d');
            $table->integer('parameter_q');
            $table->decimal('mape', 8, 4)->nullable();
            $table->decimal('rmse', 15, 4)->nullable();
            $table->enum('status_kondisi', ['aman', 'waspada', 'darurat']);
            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hasil_prediksis');
    }
};
