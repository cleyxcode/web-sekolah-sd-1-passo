<?php
// File sementara untuk debugging - HAPUS setelah selesai!
// Akses: https://sdnegeri1passo.site/cek_error.php

// Cegah akses publik
$secret = $_GET['key'] ?? '';
if ($secret !== 'debug2024') {
    die('403 Forbidden');
}

ini_set('display_errors', 1);
error_reporting(E_ALL);

echo '<pre>';
echo "=== CEK ERROR SERVER ===\n\n";

// Load Laravel
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);
$app->boot();

$db = $app->make('db');

// Cek tabel dan kolom yang kritis
$checks = [
    'gurus' => ['jabatan', 'tampil_di_website', 'urutan_tampil'],
    'siswas' => ['nama', 'nis', 'status', 'kelas_id'],
    'setting_sekolahs' => ['nama_sekolah', 'foto_hero'],
    'sessions' => ['id', 'payload'],
    'cache' => ['key', 'value'],
    'tugas' => ['id', 'judul', 'status', 'kelas_id'],
];

foreach ($checks as $table => $columns) {
    $exists = $db->getSchemaBuilder()->hasTable($table);
    echo "Tabel '{$table}': " . ($exists ? "ADA" : "TIDAK ADA") . "\n";
    if ($exists) {
        foreach ($columns as $col) {
            $colExists = $db->getSchemaBuilder()->hasColumn($table, $col);
            echo "  kolom '{$col}': " . ($colExists ? "ADA" : "❌ TIDAK ADA") . "\n";
        }
    }
    echo "\n";
}

// Cek migrations yang sudah berjalan
echo "=== MIGRATIONS YANG SUDAH BERJALAN ===\n";
try {
    $ran = $db->table('migrations')->pluck('migration')->toArray();
    foreach ($ran as $m) {
        echo "  ✓ " . $m . "\n";
    }
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}

echo "\n=== PHP VERSION: " . PHP_VERSION . " ===\n";
