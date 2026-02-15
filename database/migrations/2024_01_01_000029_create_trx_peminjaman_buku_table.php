<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trx_peminjaman_buku', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('mst_buku_id');
            $table->unsignedBigInteger('mst_siswa_id');
            $table->date('tanggal_pinjam');
            $table->date('tanggal_kembali')->nullable();
            $table->unsignedTinyInteger('status')->comment('Referensi ke sys_references dengan kategori status_pinjam');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table->index(['mst_siswa_id', 'status'], 'idx_pinjam_siswa_status');
            $table->index(['mst_buku_id', 'status'], 'idx_pinjam_buku_status');
            $table->foreign('mst_buku_id')->references('id')->on('mst_buku')->onDelete('restrict')->onUpdate('restrict');
            $table->foreign('mst_siswa_id')->references('id')->on('mst_siswa')->onDelete('restrict')->onUpdate('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trx_peminjaman_buku');
    }
};
