<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sys_references', function (Blueprint $table) {
            $table->id();
            $table->string('kategori', 50)->comment('Contoh: JENIS_KELAMIN, STATUS_SISWA, STATUS_BAYAR');
            $table->string('kode', 20)->comment('Contoh: L, P, aktif, lunas');
            $table->string('nama', 100)->comment('Label yang muncul di UI');
            $table->integer('urutan')->default(0);
            $table->timestamps();
            $table->softDeletes();
            $table->index('kategori');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sys_references');
    }
};
