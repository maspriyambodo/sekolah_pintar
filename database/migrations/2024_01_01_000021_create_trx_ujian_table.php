<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trx_ujian', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('mst_mapel_id')->nullable();
            $table->unsignedBigInteger('mst_kelas_id')->nullable();
            $table->enum('jenis', ['harian', 'uts', 'uas'])->nullable();
            $table->enum('semester', ['ganjil', 'genap'])->nullable();
            $table->date('tanggal')->nullable();

            $table->foreign('mst_mapel_id')->references('id')->on('mst_mapel')->onDelete('restrict')->onUpdate('restrict');
            $table->foreign('mst_kelas_id')->references('id')->on('mst_kelas')->onDelete('restrict')->onUpdate('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trx_ujian');
    }
};
