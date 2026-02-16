<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trx_ujian_jawaban', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('trx_ujian_user_id');
            $table->unsignedBigInteger('mst_soal_id');
            $table->unsignedBigInteger('mst_soal_opsi_id')->nullable()->comment('ID opsi yang dipilih jika pilihan ganda');
            $table->text('jawaban_teks')->nullable()->comment('Jika soal essay');
            $table->tinyInteger('is_benar')->default(0);
            $table->tinyInteger('ragu_ragu')->default(0);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table->foreign('trx_ujian_user_id')->references('id')->on('trx_ujian_user')->onDelete('cascade')->onUpdate('restrict');
            $table->foreign('mst_soal_id')->references('id')->on('mst_soal')->onDelete('cascade')->onUpdate('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trx_ujian_jawaban');
    }
};