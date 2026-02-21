<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mst_ekstrakurikuler', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('kode', 20)->unique();
            $table->string('nama', 100);
            $table->text('deskripsi')->nullable();
            $table->unsignedBigInteger('pembina_guru_id')->nullable();
            $table->string('hari', 20)->nullable();
            $table->time('jam_mulai')->nullable();
            $table->time('jam_selesai')->nullable();
            $table->string('lokasi', 100)->nullable();
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();

            $table->foreign('pembina_guru_id')->references('id')->on('mst_guru')->onDelete('set null')->onUpdate('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mst_ekstrakurikuler');
    }
};
