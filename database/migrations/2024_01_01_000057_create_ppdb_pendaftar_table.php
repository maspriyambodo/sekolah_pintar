<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ppdb_pendaftar', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('mst_sekolah_id');
            $table->unsignedBigInteger('ppdb_gelombang_id');
            $table->string('no_pendaftaran', 20)->unique()->comment('Generate otomatis (mis: PPDB-2025-001)');
            $table->string('nama_lengkap', 255);
            $table->string('email', 100);
            $table->string('password', 255)->comment('Untuk calon siswa login cek status');
            $table->string('nisn', 20)->nullable();
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('telp_hp', 20)->nullable();
            $table->string('asal_sekolah', 255)->nullable();
            $table->enum('status_pendaftaran', ['draft', 'terverifikasi', 'seleksi', 'diterima', 'cadangan', 'ditolak'])->default('draft');
            $table->unsignedBigInteger('pilihan_jurusan_id')->nullable()->comment('Opsional untuk SMK/SMA');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();

            $table->foreign('mst_sekolah_id')->references('id')->on('mst_sekolah')->onDelete('restrict');
            $table->foreign('ppdb_gelombang_id')->references('id')->on('ppdb_gelombang')->onDelete('restrict');
            $table->index('mst_sekolah_id');
            $table->index('ppdb_gelombang_id');
            $table->index('no_pendaftaran');
            $table->index('email');
            $table->index('status_pendaftaran');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ppdb_pendaftar');
    }
};
