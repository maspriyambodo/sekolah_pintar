<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sys_menus', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('parent_id')->nullable()->comment('ID menu induk untuk sub-menu');
            $table->unsignedBigInteger('sys_permission_id')->nullable()->comment('Relasi ke permission untuk akses menu');
            $table->string('nama_menu', 100);
            $table->string('url', 100)->nullable();
            $table->string('icon', 50)->nullable()->comment('Class icon (misal: fa-user, bi-grid)');
            $table->integer('urutan')->default(0);
            $table->tinyInteger('is_active')->default(1);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table->foreign('parent_id')->references('id')->on('sys_menus')->onDelete('cascade')->onUpdate('restrict');
            $table->foreign('sys_permission_id')->references('id')->on('sys_permissions')->onDelete('set null')->onUpdate('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sys_menus');
    }
};