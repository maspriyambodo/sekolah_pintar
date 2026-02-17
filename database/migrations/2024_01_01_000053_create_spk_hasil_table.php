<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spk_hasil', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('mst_siswa_id');
            $table->decimal('total_skor', 10, 4);
            $table->integer('peringkat')->nullable();
            $table->string('periode', 50)->nullable()->comment('Contoh: Beasiswa Semester Ganjil 2026');
            $table->timestamp('created_at')->nullable();

            $table->foreign('mst_siswa_id')->references('id')->on('mst_siswa')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spk_hasil');
    }
};
