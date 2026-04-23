<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('setting_sekolahs', function (Blueprint $table) {
            $table->string('foto_hero')->nullable()->after('logo')
                ->comment('Foto utama yang ditampilkan di halaman beranda');
        });
    }

    public function down(): void
    {
        Schema::table('setting_sekolahs', function (Blueprint $table) {
            $table->dropColumn('foto_hero');
        });
    }
};
