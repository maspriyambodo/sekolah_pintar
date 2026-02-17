<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trx_presensi', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('mst_guru_mapel_id');
            $table->unsignedBigInteger('mst_siswa_id');
            $table->date('tanggal');
            $table->time('jam_masuk')->nullable();
            $table->tinyInteger('status')->comment('Referensi ke sys_references dengan kategori status_presensi');
            $table->string('keterangan', 255)->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();

            $table->foreign('mst_guru_mapel_id')->references('id')->on('mst_guru_mapel')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('mst_siswa_id')->references('id')->on('mst_siswa')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trx_presensi');
    }
};
