<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ppdb_dokumen', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('ppdb_pendaftar_id');
            $table->string('jenis_dokumen', 50)->comment('KK, Ijazah, Foto, dll');
            $table->string('file_name', 255)->comment('Nama file asli');
            $table->string('mime_type', 100)->comment('MIME type file');
            $table->unsignedInteger('file_size')->comment('Ukuran file dalam bytes');
            $table->string('file_path', 255)->comment('Path di Minio');
            $table->boolean('verifikasi_status')->default(false);
            $table->text('catatan_admin')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table->foreign('ppdb_pendaftar_id')->references('id')->on('ppdb_pendaftar')->onDelete('cascade');
            $table->index('ppdb_pendaftar_id');
            $table->index('jenis_dokumen');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ppdb_dokumen');
    }
};
