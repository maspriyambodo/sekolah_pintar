<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mst_soal', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('mst_mapel_id');
            $table->text('pertanyaan');
            $table->unsignedTinyInteger('tipe')->comment('Referensi ke sys_references dengan kategori tipe_soal (1: Pilihan Ganda, 2: Essay)');
            $table->unsignedTinyInteger('tingkat_kesulitan')->comment('Referensi ke sys_references dengan kategori tingkat_kesulitan');
            $table->string('media_path', 255)->nullable()->comment('Path untuk gambar/audio soal');
            $table->timestamp('deleted_at')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table->foreign('mst_mapel_id')->references('id')->on('mst_mapel')->onDelete('cascade')->onUpdate('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mst_soal');
    }
};