<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mst_materi', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('mst_guru_mapel_id');
            $table->string('judul', 255);
            $table->text('deskripsi')->nullable();
            $table->string('file_materi', 255)->nullable();
            $table->string('link_video', 255)->nullable();
            $table->tinyInteger('status')->default(1)->comment('1: Aktif, 0: Draft');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();

            $table->foreign('mst_guru_mapel_id')->references('id')->on('mst_guru_mapel')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mst_materi');
    }
};
