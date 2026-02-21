<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trx_organisasi_anggota', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('organisasi_id');
            $table->unsignedBigInteger('siswa_id');
            $table->unsignedBigInteger('jabatan_id');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai')->nullable();
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();

            $table->unique(['organisasi_id', 'siswa_id'], 'uniq_org_siswa_periode');

            $table->foreign('organisasi_id')->references('id')->on('mst_organisasi')->onDelete('cascade')->onUpdate('restrict');
            $table->foreign('siswa_id')->references('id')->on('mst_siswa')->onDelete('cascade')->onUpdate('restrict');
            $table->foreign('jabatan_id')->references('id')->on('mst_organisasi_jabatan')->onDelete('restrict')->onUpdate('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trx_organisasi_anggota');
    }
};
