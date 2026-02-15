<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trx_bk_kasus', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('mst_siswa_id')->nullable();
            $table->unsignedBigInteger('mst_guru_id')->nullable();
            $table->unsignedBigInteger('mst_bk_kategori_id')->nullable();
            $table->unsignedBigInteger('mst_bk_jenis_id')->nullable();
            $table->string('judul_kasus', 150)->nullable();
            $table->text('deskripsi_masalah')->nullable();
            $table->enum('status', ['dibuka', 'proses', 'selesai', 'dirujuk'])->nullable();
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->timestamp('deleted_at')->nullable();

            $table->index(['mst_siswa_id', 'status'], 'idx_bk_kasus_siswa');
            $table->index('mst_guru_id', 'idx_bk_kasus_guru');
            $table->foreign('mst_siswa_id')->references('id')->on('mst_siswa')->onDelete('restrict')->onUpdate('restrict');
            $table->foreign('mst_guru_id')->references('id')->on('mst_guru')->onDelete('restrict')->onUpdate('restrict');
            $table->foreign('mst_bk_kategori_id')->references('id')->on('mst_bk_kategori')->onDelete('restrict')->onUpdate('restrict');
            $table->foreign('mst_bk_jenis_id')->references('id')->on('mst_bk_jenis')->onDelete('restrict')->onUpdate('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trx_bk_kasus');
    }
};
