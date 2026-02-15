<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trx_bk_sesi', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('trx_bk_kasus_id')->nullable();
            $table->date('tanggal')->nullable();
            $table->enum('metode', ['tatap_muka', 'online', 'telepon'])->nullable();
            $table->text('catatan')->nullable();

            $table->foreign('trx_bk_kasus_id')->references('id')->on('trx_bk_kasus')->onDelete('restrict')->onUpdate('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trx_bk_sesi');
    }
};
