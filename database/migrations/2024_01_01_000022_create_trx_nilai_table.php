<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trx_nilai', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('trx_ujian_id')->nullable();
            $table->unsignedBigInteger('mst_siswa_id')->nullable();
            $table->decimal('nilai', 5, 2)->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table->unique(['trx_ujian_id', 'mst_siswa_id'], 'uq_nilai');
            $table->index(['trx_ujian_id', 'mst_siswa_id'], 'idx_nilai_ujian_siswa');
            $table->index('mst_siswa_id', 'idx_nilai_siswa');
            $table->foreign('trx_ujian_id')->references('id')->on('trx_ujian')->onDelete('restrict')->onUpdate('restrict');
            $table->foreign('mst_siswa_id')->references('id')->on('mst_siswa')->onDelete('restrict')->onUpdate('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trx_nilai');
    }
};
