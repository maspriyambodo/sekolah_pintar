<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mst_guru_mapel', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('mst_guru_id')->nullable();
            $table->unsignedBigInteger('mst_mapel_id')->nullable();

            $table->unique(['mst_guru_id', 'mst_mapel_id'], 'uq_gm');
            $table->foreign('mst_guru_id')->references('id')->on('mst_guru')->onDelete('restrict')->onUpdate('restrict');
            $table->foreign('mst_mapel_id')->references('id')->on('mst_mapel')->onDelete('restrict')->onUpdate('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mst_guru_mapel');
    }
};
