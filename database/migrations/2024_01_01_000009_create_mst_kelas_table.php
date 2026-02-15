<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mst_kelas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nama_kelas', 20)->nullable();
            $table->integer('tingkat')->nullable();
            $table->string('tahun_ajaran', 9)->nullable();
            $table->unsignedBigInteger('wali_guru_id')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mst_kelas');
    }
};
