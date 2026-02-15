<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trx_absensi_siswa', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('mst_siswa_id')->nullable();
            $table->date('tanggal')->nullable();
            $table->unsignedTinyInteger('status')->comment('Referensi ke sys_references dengan kategori status_absensi');
            $table->text('keterangan')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table->unique(['mst_siswa_id', 'tanggal'], 'uq_as');
            $table->index(['mst_siswa_id', 'tanggal'], 'idx_absensi_siswa_tanggal');
            $table->index('status', 'idx_absensi_siswa_status');
            $table->foreign('mst_siswa_id')->references('id')->on('mst_siswa')->onDelete('restrict')->onUpdate('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trx_absensi_siswa');
    }
};
