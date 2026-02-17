<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spk_penilaian', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('mst_siswa_id');
            $table->unsignedBigInteger('spk_kriteria_id');
            $table->decimal('nilai', 10, 2)->default(0.00);
            $table->string('tahun_ajaran', 9)->nullable();
            $table->timestamp('created_at')->nullable();

            $table->foreign('mst_siswa_id')->references('id')->on('mst_siswa')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('spk_kriteria_id')->references('id')->on('spk_kriteria')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spk_penilaian');
    }
};
