<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pendaftarans', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->string('link_pendaftaran');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pendaftarans');
    }
};
