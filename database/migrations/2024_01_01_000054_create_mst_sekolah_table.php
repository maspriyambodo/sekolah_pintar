<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mst_sekolah', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('uuid', 36)->unique()->comment('Untuk URL unik/slug (misal: sekolah-a.app.com)');
            $table->string('npsn', 20)->nullable();
            $table->string('nama_sekolah', 255);
            $table->text('alamat')->nullable();
            $table->string('logo_path', 255)->nullable()->comment('Path ke Minio');
            $table->boolean('is_active')->default(true);
            $table->string('subscription_plan', 50)->default('free')->comment('Integrasi billing nantinya');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mst_sekolah');
    }
};
