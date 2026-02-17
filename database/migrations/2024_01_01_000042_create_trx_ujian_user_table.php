<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trx_ujian_user', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('trx_ujian_id');
            $table->unsignedBigInteger('mst_siswa_id');
            $table->timestamp('waktu_mulai')->nullable();
            $table->timestamp('waktu_selesai')->nullable();
            $table->unsignedTinyInteger('status')->default(1)->comment('1: Belum mulai, 2: Mengerjakan, 3: Selesai');
            $table->unsignedInteger('sisa_waktu')->nullable()->comment('Dalam hitungan detik');
            $table->integer('total_benar')->default(0);
            $table->integer('total_salah')->default(0);
            $table->decimal('nilai_akhir', 5, 2)->default(0.00);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();

            $table->foreign('trx_ujian_id')->references('id')->on('trx_ujian')->onDelete('cascade')->onUpdate('restrict');
            $table->foreign('mst_siswa_id')->references('id')->on('mst_siswa')->onDelete('cascade')->onUpdate('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trx_ujian_user');
    }
};