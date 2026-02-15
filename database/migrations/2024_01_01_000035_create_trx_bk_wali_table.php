<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trx_bk_wali', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('trx_bk_kasus_id');
            $table->unsignedBigInteger('mst_wali_murid_id');
            $table->unsignedTinyInteger('peran')->comment('Referensi ke sys_references dengan kategori peran_wali_bk');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();

            $table->foreign('trx_bk_kasus_id')->references('id')->on('trx_bk_kasus')->onDelete('cascade')->onUpdate('restrict');
            $table->foreign('mst_wali_murid_id')->references('id')->on('mst_wali_murid')->onDelete('restrict')->onUpdate('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trx_bk_wali');
    }
};
