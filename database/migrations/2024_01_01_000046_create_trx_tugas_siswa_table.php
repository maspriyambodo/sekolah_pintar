<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trx_tugas_siswa', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('mst_tugas_id');
            $table->unsignedBigInteger('mst_siswa_id');
            $table->text('jawaban_teks')->nullable()->comment('Jika tugas diketik langsung');
            $table->string('file_siswa', 255)->nullable()->comment('Path file jawaban siswa');
            $table->timestamp('waktu_kumpul')->nullable();
            $table->decimal('nilai', 5, 2)->default(0);
            $table->text('catatan_guru')->nullable()->comment('Feedback dari guru');
            $table->tinyInteger('status_kumpul')->default(0)->comment('0: Belum, 1: Tepat Waktu, 2: Terlambat');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();

            $table->foreign('mst_tugas_id')->references('id')->on('mst_tugas')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('mst_siswa_id')->references('id')->on('mst_siswa')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trx_tugas_siswa');
    }
};
