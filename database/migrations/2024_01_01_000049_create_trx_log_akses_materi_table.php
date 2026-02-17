<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trx_log_akses_materi', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('mst_materi_id');
            $table->unsignedBigInteger('mst_siswa_id');
            $table->timestamp('waktu_akses')->useCurrent();
            $table->integer('durasi_detik')->default(0)->comment('Lama siswa membaca materi');
            $table->string('perangkat', 255)->nullable()->comment('Info browser/HP');

            $table->foreign('mst_materi_id')->references('id')->on('mst_materi')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('mst_siswa_id')->references('id')->on('mst_siswa')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trx_log_akses_materi');
    }
};
