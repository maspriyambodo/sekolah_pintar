<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trx_forum', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('mst_guru_mapel_id');
            $table->unsignedBigInteger('sys_user_id')->comment('Pengirim pesan (Guru/Siswa)');
            $table->unsignedBigInteger('parent_id')->nullable()->comment('ID pesan utama jika ini adalah balasan');
            $table->string('judul', 255)->nullable()->comment('Hanya diisi untuk topik baru');
            $table->text('pesan');
            $table->string('file_lampiran', 255)->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();

            $table->foreign('mst_guru_mapel_id')->references('id')->on('mst_guru_mapel')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('sys_user_id')->references('id')->on('sys_users')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trx_forum');
    }
};
