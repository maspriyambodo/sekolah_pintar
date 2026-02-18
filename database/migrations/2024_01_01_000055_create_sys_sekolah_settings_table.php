<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sys_sekolah_settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('mst_sekolah_id');
            $table->string('key', 100)->comment('misal: tahun_ajaran_aktif, format_rapor');
            $table->text('value')->nullable();

            $table->foreign('mst_sekolah_id')
                ->references('id')
                ->on('mst_sekolah')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sys_sekolah_settings');
    }
};
