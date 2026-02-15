<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mst_tarif_spp', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('mst_kelas_id')->nullable();
            $table->string('tahun_ajaran', 9)->comment('Contoh: 2023/2024');
            $table->decimal('nominal', 10, 2)->default(0.00);
            $table->string('keterangan', 255)->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->softDeletes();

            $table->foreign('mst_kelas_id')->references('id')->on('mst_kelas')->onDelete('restrict')->onUpdate('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mst_tarif_spp');
    }
};
