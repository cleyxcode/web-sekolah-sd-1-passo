<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gurus', function (Blueprint $table) {
            $table->string('jabatan')->nullable()->after('nama');
            $table->boolean('tampil_di_website')->default(true)->after('jabatan');
            $table->integer('urutan_tampil')->default(99)->after('tampil_di_website');
        });
    }

    public function down(): void
    {
        Schema::table('gurus', function (Blueprint $table) {
            $table->dropColumn(['jabatan', 'tampil_di_website', 'urutan_tampil']);
        });
    }
};
