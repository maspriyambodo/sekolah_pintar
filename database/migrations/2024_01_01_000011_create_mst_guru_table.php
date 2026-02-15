<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mst_guru', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('sys_user_id');
            $table->string('nip', 20)->unique();
            $table->string('nama', 100);
            $table->unsignedTinyInteger('jenis_kelamin')->comment('Referensi ke sys_references dengan kategori jenis_kelamin');
            $table->date('tanggal_lahir')->nullable();
            $table->text('alamat')->nullable();
            $table->string('no_hp', 20)->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table->foreign('sys_user_id')->references('id')->on('sys_users')->onDelete('restrict')->onUpdate('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mst_guru');
    }
};
