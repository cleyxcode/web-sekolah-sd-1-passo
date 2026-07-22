<?php
// Script Debugging Error 500 untuk Production
// URL Akses: https://sdnegeri1passo.site/debug_log.php?key=rahasia123

// 1. Kunci Keamanan Sederhana
$key = $_GET['key'] ?? '';
if ($key !== 'rahasia123') {
    http_response_code(403);
    die('Akses Ditolak. Gunakan parameter ?key=rahasia123 di URL.');
}

ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "<html><head><title>Debug Info</title>";
echo "<style>
    body { font-family: monospace; background: #1e1e1e; color: #00ff00; padding: 20px; }
    h2 { color: #ffeb3b; border-bottom: 1px solid #555; padding-bottom: 5px; }
    .error { color: #ff5252; }
    .box { background: #2d2d2d; padding: 15px; border-radius: 5px; margin-bottom: 20px; overflow-x: auto; }
</style>";
echo "</head><body>";

echo "<h1>🔍 Diagnosa Server Production Laravel</h1>";

// 2. Cek File Log Laravel
echo "<h2>1. Isi Log Error Laravel Terakhir (storage/logs/laravel.log)</h2>";
$logPath = __DIR__ . '/../storage/logs/laravel.log';

if (file_exists($logPath)) {
    // Ambil 100 baris terakhir dari log
    $logContent = tailCustom($logPath, 150);
    if (empty(trim($logContent))) {
         echo "<div class='box'>Log Laravel kosong atau tidak ada error baru.</div>";
    } else {
         echo "<div class='box' style='white-space: pre-wrap; font-size: 12px;'>" . htmlspecialchars($logContent) . "</div>";
    }
} else {
    echo "<div class='box error'>File log tidak ditemukan di: $logPath</div>";
}

// 3. Test Koneksi Database Laravel & Cek Tabel/Kolom
echo "<h2>2. Cek Struktur Database MySQL</h2>";
echo "<div class='box'>";
try {
    // Bootstrap Laravel untuk ambil koneksi DB
    require __DIR__ . '/../vendor/autoload.php';
    $app = require __DIR__ . '/../bootstrap/app.php';
    $kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);
    $app->boot();

    $db = $app->make('db');
    $pdo = $db->connection()->getPdo();
    echo "✅ Koneksi Database Berhasil!<br><br>";

    // Cek kolom yang rawan error
    $tabelCek = [
        'gurus' => ['jabatan', 'tampil_di_website', 'urutan_tampil'],
        'siswas' => ['kelas_id', 'status'],
        'setting_sekolahs' => ['foto_hero', 'nama_sekolah']
    ];

    foreach ($tabelCek as $tabel => $koloms) {
        $hasTable = $db->getSchemaBuilder()->hasTable($tabel);
        if ($hasTable) {
            echo "Tabel <strong>$tabel</strong> : ADA<br>";
            foreach ($koloms as $col) {
                $hasCol = $db->getSchemaBuilder()->hasColumn($tabel, $col);
                if ($hasCol) {
                    echo "&nbsp;&nbsp;➔ Kolom <em>$col</em> : ADA<br>";
                } else {
                    echo "&nbsp;&nbsp;➔ <span class='error'>Kolom <em>$col</em> : TIDAK ADA (Ini mungkin penyebab error!)</span><br>";
                }
            }
        } else {
            echo "<span class='error'>Tabel <strong>$tabel</strong> : TIDAK ADA!</span><br>";
        }
        echo "<br>";
    }

} catch (\Exception $e) {
    echo "<span class='error'>Error Database: " . htmlspecialchars($e->getMessage()) . "</span>";
}
echo "</div>";

// Helper function untuk membaca tail file tanpa load ke memori semua
function tailCustom($filepath, $lines = 100) {
    $f = @fopen($filepath, "rb");
    if ($f === false) return false;
    $cursor = -1;
    fseek($f, $cursor, SEEK_END);
    $char = fgetc($f);
    $lineCount = 0;
    while ($lineCount < $lines && fseek($f, $cursor, SEEK_END) !== -1) {
        $char = fgetc($f);
        if ($char === "\n") {
            $lineCount++;
        }
        $cursor--;
    }
    $cursor += 2; // Lewati newline terakhir
    fseek($f, $cursor, SEEK_END);
    $output = fread($f, filesize($filepath));
    fclose($f);
    return $output;
}

echo "</body></html>";
