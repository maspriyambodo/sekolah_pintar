<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('mst_golongan', function (Blueprint $table) {
            $table->id();
            $table->string('pangkat', 50)->comment('Contoh: Penata Muda, Pembina');
            $table->char('golongan_ruang', 5)->comment('Contoh: III/a, IV/b');
            $table->string('jabatan', 50)->nullable()->comment('Contoh: Guru Ahli Pertama, Guru Ahli Madya');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mst_golongan');
    }
};