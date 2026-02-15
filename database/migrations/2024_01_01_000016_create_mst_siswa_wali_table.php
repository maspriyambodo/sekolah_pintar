<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mst_siswa_wali', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('mst_siswa_id')->nullable();
            $table->unsignedBigInteger('mst_wali_id')->nullable();
            $table->unsignedTinyInteger('hubungan')->comment('Referensi ke sys_references dengan kategori hubungan_wali');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();

            $table->unique(['mst_siswa_id', 'mst_wali_id'], 'uq_sw');
            $table->foreign('mst_siswa_id')->references('id')->on('mst_siswa')->onDelete('restrict')->onUpdate('restrict');
            $table->foreign('mst_wali_id')->references('id')->on('mst_wali')->onDelete('restrict')->onUpdate('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mst_siswa_wali');
    }
};
