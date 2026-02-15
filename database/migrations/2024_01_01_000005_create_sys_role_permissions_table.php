<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sys_role_permissions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('sys_role_id');
            $table->unsignedBigInteger('sys_permission_id');
            $table->timestamp('created_at')->nullable();

            $table->unique(['sys_role_id', 'sys_permission_id'], 'uq_role_permission');
            $table->foreign('sys_role_id')->references('id')->on('sys_roles')->onDelete('cascade')->onUpdate('restrict');
            $table->foreign('sys_permission_id')->references('id')->on('sys_permissions')->onDelete('cascade')->onUpdate('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sys_role_permissions');
    }
};
