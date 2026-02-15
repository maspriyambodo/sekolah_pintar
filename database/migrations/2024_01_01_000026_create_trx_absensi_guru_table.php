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
            $table->unsignedTinyInteger('status')->comment('Referensi ke sys_references dengan kategori status_absensi');
            $table->text('keterangan')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();

            $table->unique(['mst_guru_id', 'tanggal'], 'uq_ag');
            $table->foreign('mst_guru_id')->references('id')->on('mst_guru')->onDelete('restrict')->onUpdate('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trx_absensi_guru');
    }
};
