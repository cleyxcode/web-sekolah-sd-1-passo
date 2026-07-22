<?php
/**
 * Script fix kolom database yang mungkin kurang di server production.
 * Jalankan di server dengan: php artisan migrate --path=database/migrations/fix_server
 * 
 * Atau jalankan file ini langsung: php fix_production_columns.php
 * 
 * HAPUS FILE INI SETELAH SELESAI!
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // === FIX 1: Tambah kolom yang kurang di tabel 'gurus' ===
        Schema::table('gurus', function (Blueprint $table) {
            if (!Schema::hasColumn('gurus', 'jabatan')) {
                $table->string('jabatan')->nullable()->after('nama');
            }
            if (!Schema::hasColumn('gurus', 'tampil_di_website')) {
                $table->boolean('tampil_di_website')->default(true)->after('jabatan');
            }
            if (!Schema::hasColumn('gurus', 'urutan_tampil')) {
                $table->integer('urutan_tampil')->default(99)->after('tampil_di_website');
            }
        });

        // === FIX 2: Buat tabel 'tugas' jika belum ada ===
        if (!Schema::hasTable('tugas')) {
            Schema::create('tugas', function (Blueprint $table) {
                $table->id();
                $table->string('judul');
                $table->text('deskripsi')->nullable();
                $table->string('mata_pelajaran')->nullable();
                $table->foreignId('kelas_id')->constrained('kelas')->cascadeOnDelete();
                $table->foreignId('guru_id')->constrained('gurus')->cascadeOnDelete();
                $table->dateTime('deadline');
                $table->json('foto_tugas')->nullable();
                $table->json('file_tugas')->nullable();
                $table->enum('status', ['aktif', 'selesai', 'dibatalkan'])->default('aktif');
                $table->timestamps();
            });
        }

        // === FIX 3: Buat tabel 'komentar_tugas' jika belum ada ===
        if (!Schema::hasTable('komentar_tugas')) {
            Schema::create('komentar_tugas', function (Blueprint $table) {
                $table->id();
                $table->foreignId('tugas_id')->constrained('tugas')->cascadeOnDelete();
                $table->foreignId('guru_id')->constrained('gurus')->cascadeOnDelete();
                $table->text('komentar');
                $table->timestamps();
            });
        }

        // === FIX 4: Buat tabel 'catatan_perkembangans' jika belum ada ===
        if (!Schema::hasTable('catatan_perkembangans')) {
            Schema::create('catatan_perkembangans', function (Blueprint $table) {
                $table->id();
                $table->foreignId('siswa_id')->constrained('siswas')->cascadeOnDelete();
                $table->foreignId('guru_id')->constrained('gurus')->cascadeOnDelete();
                $table->text('catatan');
                $table->string('kategori')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        // Tidak ada rollback untuk safety
    }
};
