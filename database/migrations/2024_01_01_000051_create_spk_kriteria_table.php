<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spk_kriteria', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('kode_kriteria', 10)->unique();
            $table->string('nama_kriteria', 100);
            $table->decimal('bobot', 5, 2)->comment('Contoh: 0.25 atau 25.00');
            $table->enum('tipe', ['benefit', 'cost'])->default('benefit');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spk_kriteria');
    }
};