<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trx_rapor_detail', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('trx_rapor_id')->nullable();
            $table->unsignedBigInteger('mst_mapel_id')->nullable();
            $table->decimal('nilai_akhir', 5, 2)->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();

            $table->unique(['trx_rapor_id', 'mst_mapel_id'], 'uq_rd');
            $table->foreign('trx_rapor_id')->references('id')->on('trx_rapor')->onDelete('restrict')->onUpdate('restrict');
            $table->foreign('mst_mapel_id')->references('id')->on('mst_mapel')->onDelete('restrict')->onUpdate('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trx_rapor_detail');
    }
};
