<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mst_siswa', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('sys_user_id');
            $table->string('nis', 20)->unique();
            $table->string('nama', 100);
            $table->unsignedTinyInteger('jenis_kelamin')->comment('Referensi ke sys_references dengan kategori jenis_kelamin');
            $table->date('tanggal_lahir')->nullable();
            $table->text('alamat')->nullable();
            $table->unsignedBigInteger('mst_kelas_id')->nullable();
            $table->unsignedTinyInteger('status')->comment('Referensi ke sys_references dengan kategori status_siswa');
            $table->timestamp('deleted_at')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table->index(['mst_kelas_id', 'status'], 'idx_siswa_kelas_status');
            $table->index('nama', 'idx_siswa_nama');
            $table->index('jenis_kelamin', 'idx_siswa_jk');
            $table->foreign('sys_user_id')->references('id')->on('sys_users')->onDelete('restrict')->onUpdate('restrict');
            $table->foreign('mst_kelas_id')->references('id')->on('mst_kelas')->onDelete('restrict')->onUpdate('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mst_siswa');
    }
};
