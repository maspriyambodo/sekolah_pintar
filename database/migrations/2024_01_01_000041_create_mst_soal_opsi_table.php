<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mst_soal_opsi', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('mst_soal_id');
            $table->text('teks_opsi');
            $table->tinyInteger('is_jawaban')->default(0)->comment('1 jika ini kunci jawaban');
            $table->char('urutan', 1)->nullable()->comment('A, B, C, D, atau E');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();

            $table->foreign('mst_soal_id')->references('id')->on('mst_soal')->onDelete('cascade')->onUpdate('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mst_soal_opsi');
    }
};