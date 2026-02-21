<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trx_ekstrakurikuler_siswa', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('ekstrakurikuler_id');
            $table->unsignedBigInteger('siswa_id');
            $table->date('tanggal_daftar');
            $table->enum('status', ['aktif', 'keluar'])->default('aktif');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();

            $table->unique(['ekstrakurikuler_id', 'siswa_id'], 'uniq_ekskul_siswa');

            $table->foreign('ekstrakurikuler_id')->references('id')->on('mst_ekstrakurikuler')->onDelete('cascade')->onUpdate('restrict');
            $table->foreign('siswa_id')->references('id')->on('mst_siswa')->onDelete('cascade')->onUpdate('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trx_ekstrakurikuler_siswa');
    }
};
