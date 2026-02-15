<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trx_absensi_guru', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('mst_guru_id')->nullable();
            $table->date('tanggal')->nullable();
            $table->enum('status', ['hadir', 'izin', 'sakit', 'alpha'])->nullable();
            $table->text('keterangan')->nullable();

            $table->unique(['mst_guru_id', 'tanggal'], 'uq_ag');
            $table->foreign('mst_guru_id')->references('id')->on('mst_guru')->onDelete('restrict')->onUpdate('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trx_absensi_guru');
    }
};
