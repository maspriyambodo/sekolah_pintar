<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add foreign key for mst_kelas.wali_guru_id -> mst_guru
        Schema::table('mst_kelas', function (Blueprint $table) {
            $table->foreign('wali_guru_id')->references('id')->on('mst_guru')->onDelete('restrict')->onUpdate('restrict');
        });
    }

    public function down(): void
    {
        Schema::table('mst_kelas', function (Blueprint $table) {
            $table->dropForeign(['wali_guru_id']);
        });
    }
};
