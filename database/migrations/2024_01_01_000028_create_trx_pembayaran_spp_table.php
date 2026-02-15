<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trx_pembayaran_spp', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('mst_siswa_id');
            $table->unsignedBigInteger('mst_tarif_spp_id');
            $table->tinyInteger('bulan')->comment('1=Januari, 12=Desember');
            $table->year('tanggal');
            $table->date('tanggal_bayar');
            $table->decimal('jumlah_bayar', 10, 2);
            $table->enum('status', ['lunas', 'belum_lunas', 'pending', 'dibatalkan'])->default('pending');
            $table->enum('metode_pembayaran', ['tunai', 'transfer', 'virtual_account', 'qris'])->default('tunai');
            $table->text('keterangan')->nullable();
            $table->unsignedBigInteger('petugas_id')->nullable()->comment('User yang mencatat pembayaran');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();

            $table->unique(['mst_siswa_id', 'bulan', 'tahun'], 'uq_spp_bayar');
            $table->foreign('mst_siswa_id')->references('id')->on('mst_siswa')->onDelete('restrict')->onUpdate('restrict');
            $table->foreign('mst_tarif_spp_id')->references('id')->on('mst_tarif_spp')->onDelete('restrict')->onUpdate('restrict');
            $table->foreign('petugas_id')->references('id')->on('sys_users')->onDelete('set null')->onUpdate('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trx_pembayaran_spp');
    }
};
