<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trx_rapor', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('mst_siswa_id')->nullable();
            $table->unsignedTinyInteger('semester')->comment('Referensi ke sys_references dengan kategori semester');
            $table->string('tahun_ajaran', 9)->nullable();
            $table->decimal('total_nilai', 6, 2)->nullable();
            $table->decimal('rata_rata', 5, 2)->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();

            $table->index(['mst_siswa_id', 'semester', 'tahun_ajaran'], 'idx_rapor_siswa_semester');
            $table->foreign('mst_siswa_id')->references('id')->on('mst_siswa')->onDelete('restrict')->onUpdate('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trx_rapor');
    }
};
