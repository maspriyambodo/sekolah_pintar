<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ppdb_gelombang', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('mst_sekolah_id');
            $table->string('nama_gelombang', 100)->comment('Contoh: Gelombang 1, Jalur Prestasi');
            $table->string('tahun_ajaran', 9)->comment('Contoh: 2025/2026');
            $table->date('tgl_mulai');
            $table->date('tgl_selesai');
            $table->decimal('biaya_pendaftaran', 12, 2)->default('0.00');
            $table->boolean('is_active')->default(true);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();

            $table->foreign('mst_sekolah_id')->references('id')->on('mst_sekolah')->onDelete('cascade');
            $table->index('mst_sekolah_id');
            $table->index('tahun_ajaran');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ppdb_gelombang');
    }
};
