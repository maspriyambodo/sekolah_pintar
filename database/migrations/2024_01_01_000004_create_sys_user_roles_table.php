<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sys_user_roles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('sys_user_id');
            $table->unsignedBigInteger('sys_role_id');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();

            $table->unique(['sys_user_id', 'sys_role_id'], 'uq_user_role');
            $table->foreign('sys_user_id')->references('id')->on('sys_users')->onDelete('cascade')->onUpdate('restrict');
            $table->foreign('sys_role_id')->references('id')->on('sys_roles')->onDelete('cascade')->onUpdate('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sys_user_roles');
    }
};
