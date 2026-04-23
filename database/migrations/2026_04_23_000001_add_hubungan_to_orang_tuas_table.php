<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orang_tuas', function (Blueprint $table) {
            $table->string('hubungan')->nullable()->after('pekerjaan')
                ->comment('Hubungan dengan siswa: Ayah, Ibu, Wali, dll');
        });
    }

    public function down(): void
    {
        Schema::table('orang_tuas', function (Blueprint $table) {
            $table->dropColumn('hubungan');
        });
    }
};
