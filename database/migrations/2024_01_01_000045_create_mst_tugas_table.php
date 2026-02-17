<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mst_tugas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('mst_guru_mapel_id')->comment('Relasi ke guru & mapel');
            $table->unsignedBigInteger('mst_kelas_id')->comment('Target kelas');
            $table->string('judul', 255)->nullable(false);
            $table->text('deskripsi')->nullable()->comment('Instruksi tugas');
            $table->string('file_lampiran', 255)->nullable()->comment('Path file soal jika ada');
            $table->dateTime('tenggat_waktu')->nullable(false);
            $table->tinyInteger('status')->default(1)->comment('1: Aktif, 0: Draft/Selesai');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();

            $table->foreign('mst_guru_mapel_id')->references('id')->on('mst_guru_mapel')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('mst_kelas_id')->references('id')->on('mst_kelas')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mst_tugas');
    }
};
