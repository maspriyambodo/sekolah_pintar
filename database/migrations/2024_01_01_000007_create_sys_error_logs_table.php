<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sys_error_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('level', ['info', 'warning', 'error', 'critical']);
            $table->text('message');
            $table->string('file', 255)->nullable();
            $table->integer('line')->nullable();
            $table->text('trace')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sys_error_logs');
    }
};
