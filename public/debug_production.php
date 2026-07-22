<?php
/**
 * DEBUG PRODUCTION - SD Negeri 1 Passo
 * =====================================
 * Upload file ini ke folder public/ di server production.
 *
 * Akses:
 *   https://www.sdnegeri1passo.site/debug_production.php?key=sdnegeri1passo2026
 *
 * PENTING: HAPUS file ini setelah selesai debugging!
 */

declare(strict_types=1);

// ─── KUNCI KEAMANAN ───────────────────────────────────────────────
const DEBUG_SECRET_KEY = 'sdnegeri1passo2026';

if (($_GET['key'] ?? '') !== DEBUG_SECRET_KEY) {
    http_response_code(403);
    exit('403 Forbidden - Tambahkan ?key=' . DEBUG_SECRET_KEY . ' di URL');
}

ini_set('display_errors', '1');
error_reporting(E_ALL);
header('Content-Type: text/html; charset=utf-8');

$basePath = realpath(__DIR__ . '/..') ?: dirname(__DIR__);

// ─── HELPER ───────────────────────────────────────────────────────
function ok(string $msg): void
{
    echo "<div class='ok'>✅ {$msg}</div>";
}

function fail(string $msg): void
{
    echo "<div class='fail'>❌ {$msg}</div>";
}

function warn(string $msg): void
{
    echo "<div class='warn'>⚠️ {$msg}</div>";
}

function info(string $msg): void
{
    echo "<div class='info'>ℹ️ {$msg}</div>";
}

function section(string $title): void
{
    echo "<h2>{$title}</h2><div class='box'>";
}

function endSection(): void
{
    echo '</div>';
}

function tailFile(string $path, int $lines = 120): string
{
    if (!is_readable($path)) {
        return '';
    }

    $file = new SplFileObject($path, 'r');
    $file->seek(PHP_INT_MAX);
    $lastLine = $file->key();
    $start = max(0, $lastLine - $lines);
    $output = [];

    $file->seek($start);
    while (!$file->eof()) {
        $output[] = $file->current();
        $file->next();
    }

    return implode('', $output);
}

function isWritableDir(string $path): bool
{
    return is_dir($path) && is_writable($path);
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Debug Production - SD Negeri 1 Passo</title>
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', system-ui, sans-serif;
            background: #0f172a;
            color: #e2e8f0;
            margin: 0;
            padding: 20px;
            line-height: 1.5;
        }
        h1 { color: #38bdf8; margin-bottom: 5px; }
        h2 {
            color: #fbbf24;
            border-bottom: 1px solid #334155;
            padding-bottom: 8px;
            margin-top: 30px;
        }
        .subtitle { color: #94a3b8; margin-bottom: 25px; }
        .box {
            background: #1e293b;
            border: 1px solid #334155;
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 10px;
        }
        .ok { color: #4ade80; margin: 4px 0; }
        .fail { color: #f87171; margin: 4px 0; font-weight: 600; }
        .warn { color: #fbbf24; margin: 4px 0; }
        .info { color: #94a3b8; margin: 4px 0; }
        pre {
            background: #020617;
            border: 1px solid #334155;
            border-radius: 6px;
            padding: 14px;
            overflow-x: auto;
            font-size: 12px;
            color: #cbd5e1;
            white-space: pre-wrap;
            word-break: break-word;
        }
        .badge {
            display: inline-block;
            background: #dc2626;
            color: white;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 12px;
            margin-left: 8px;
        }
    </style>
</head>
<body>

<h1>🔍 Debug Production Laravel</h1>
<p class="subtitle">
    Server: <?= htmlspecialchars($_SERVER['HTTP_HOST'] ?? 'unknown') ?> |
    Waktu: <?= date('Y-m-d H:i:s T') ?> |
    <span class="badge">HAPUS FILE INI SETELAH SELESAI</span>
</p>

<?php
// ═══════════════════════════════════════════════════════════════════
// 1. CEK LINGKUNGAN PHP & FILE PENTING
// ═══════════════════════════════════════════════════════════════════
section('1. Lingkungan Server & File Penting');

info('PHP Version: ' . PHP_VERSION);
info('Base Path: ' . $basePath);
info('Document Root: ' . ($_SERVER['DOCUMENT_ROOT'] ?? '-'));

$requiredExtensions = ['pdo', 'mbstring', 'openssl', 'tokenizer', 'xml', 'ctype', 'json', 'fileinfo'];
foreach ($requiredExtensions as $ext) {
    extension_loaded($ext) ? ok("Extension {$ext}") : fail("Extension {$ext} TIDAK ADA");
}

$requiredFiles = [
    'vendor/autoload.php' => $basePath . '/vendor/autoload.php',
    'bootstrap/app.php' => $basePath . '/bootstrap/app.php',
    '.env' => $basePath . '/.env',
    'artisan' => $basePath . '/artisan',
];

foreach ($requiredFiles as $label => $path) {
    file_exists($path) ? ok("File {$label} ada") : fail("File {$label} TIDAK DITEMUKAN: {$path}");
}

$writableDirs = [
    'storage' => $basePath . '/storage',
    'storage/framework' => $basePath . '/storage/framework',
    'storage/framework/cache' => $basePath . '/storage/framework/cache',
    'storage/framework/sessions' => $basePath . '/storage/framework/sessions',
    'storage/framework/views' => $basePath . '/storage/framework/views',
    'storage/logs' => $basePath . '/storage/logs',
    'bootstrap/cache' => $basePath . '/bootstrap/cache',
];

foreach ($writableDirs as $label => $path) {
    if (!is_dir($path)) {
        fail("Folder {$label} tidak ada");
    } elseif (!isWritableDir($path)) {
        fail("Folder {$label} TIDAK BISA DITULIS (chmod 775 atau 777)");
    } else {
        ok("Folder {$label} writable");
    }
}

endSection();

// ═══════════════════════════════════════════════════════════════════
// 2. CEK KONFIGURASI .ENV (tanpa menampilkan password)
// ═══════════════════════════════════════════════════════════════════
section('2. Konfigurasi .env');

$envPath = $basePath . '/.env';
if (file_exists($envPath)) {
    $envContent = file_get_contents($envPath) ?: '';
    $envKeys = ['APP_NAME', 'APP_ENV', 'APP_KEY', 'APP_DEBUG', 'APP_URL', 'DB_CONNECTION', 'DB_HOST', 'DB_DATABASE', 'DB_USERNAME', 'SESSION_DRIVER', 'CACHE_STORE'];

    foreach ($envKeys as $key) {
        if (preg_match('/^' . preg_quote($key, '/') . '=(.*)$/m', $envContent, $m)) {
            $value = trim($m[1], " \t\n\r\0\x0B\"'");
            if ($key === 'APP_KEY') {
                empty($value) ? fail('APP_KEY KOSONG! Jalankan: php artisan key:generate') : ok('APP_KEY sudah di-set');
            } elseif (in_array($key, ['DB_PASSWORD', 'DB_USERNAME'], true)) {
                empty($value) ? warn("{$key} kosong") : ok("{$key} sudah di-set (disembunyikan)");
            } elseif ($key === 'APP_DEBUG') {
                $value === 'true' ? warn('APP_DEBUG=true (sebaiknya false di production)') : ok("APP_DEBUG={$value}");
            } else {
                ok("{$key} = {$value}");
            }
        } else {
            fail("{$key} tidak ditemukan di .env");
        }
    }
} else {
    fail('.env tidak ada - ini penyebab umum error 500!');
}

endSection();

// ═══════════════════════════════════════════════════════════════════
// 3. BOOTSTRAP LARAVEL
// ═══════════════════════════════════════════════════════════════════
section('3. Bootstrap Laravel');

$app = null;
$bootError = null;

try {
    require $basePath . '/vendor/autoload.php';
    $app = require $basePath . '/bootstrap/app.php';
    $app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();
    ok('Laravel berhasil di-bootstrap');
    info('Laravel Version: ' . app()->version());
    info('Environment: ' . app()->environment());
    info('Debug Mode: ' . (config('app.debug') ? 'ON' : 'OFF'));
} catch (Throwable $e) {
    $bootError = $e;
    fail('GAGAL bootstrap Laravel: ' . $e->getMessage());
    echo '<pre>' . htmlspecialchars($e->getTraceAsString()) . '</pre>';
}

endSection();

// ═══════════════════════════════════════════════════════════════════
// 4. CEK DATABASE & KOLOM KRITIS
// ═══════════════════════════════════════════════════════════════════
if ($app !== null) {
    section('4. Database & Struktur Tabel');

    try {
        $db = $app->make('db');
        $db->connection()->getPdo();
        ok('Koneksi database berhasil');

        $tableChecks = [
            'beritas' => ['id', 'judul', 'slug', 'status', 'published_at'],
            'profil_sekolahs' => ['id', 'jenis', 'konten'],
            'galeris' => ['id', 'judul', 'foto'],
            'pendaftarans' => ['id', 'is_active', 'link_pendaftaran'],
            'gurus' => ['id', 'nama', 'jabatan', 'tampil_di_website', 'urutan_tampil', 'foto'],
            'setting_sekolahs' => ['id', 'nama_sekolah'],
            'sessions' => ['id', 'payload'],
            'cache' => ['key', 'value'],
            'migrations' => ['id', 'migration'],
        ];

        $missingColumns = [];

        foreach ($tableChecks as $table => $columns) {
            $schema = $db->getSchemaBuilder();
            if (!$schema->hasTable($table)) {
                fail("Tabel '{$table}' TIDAK ADA");
                $missingColumns[] = "tabel:{$table}";
                continue;
            }

            ok("Tabel '{$table}' ada");
            foreach ($columns as $col) {
                if ($schema->hasColumn($table, $col)) {
                    info("  └─ kolom '{$col}' OK");
                } else {
                    fail("  └─ kolom '{$col}' TIDAK ADA ← kemungkinan penyebab error 500!");
                    $missingColumns[] = "{$table}.{$col}";
                }
            }
        }

        if (!empty($missingColumns)) {
            echo '<br>';
            warn('Kolom/tabel yang kurang: ' . implode(', ', $missingColumns));
            warn('Solusi: jalankan di server → php artisan migrate --force');
        }

        // Cek migration yang belum jalan
        echo '<br><strong>Migration terakhir yang sudah jalan:</strong><br>';
        $migrations = $db->table('migrations')->orderBy('id', 'desc')->limit(10)->pluck('migration');
        foreach ($migrations as $m) {
            info('  ✓ ' . $m);
        }

    } catch (Throwable $e) {
        fail('Error database: ' . $e->getMessage());
        echo '<pre>' . htmlspecialchars($e->getTraceAsString()) . '</pre>';
    }

    endSection();

    // ═══════════════════════════════════════════════════════════════════
    // 5. SIMULASI QUERY HALAMAN PUBLIK (HomeController)
    // ═══════════════════════════════════════════════════════════════════
    section('5. Simulasi Query Halaman Beranda (penyebab error 500)');

    $queries = [
        'Berita (3 terbaru)' => function () {
            return \App\Models\Berita::where('status', 'publish')
                ->orderBy('published_at', 'desc')
                ->take(3)
                ->get();
        },
        'Profil Sekolah - Visi' => function () {
            return \App\Models\ProfilSekolah::where('jenis', 'visi')->first();
        },
        'Profil Sekolah - Misi' => function () {
            return \App\Models\ProfilSekolah::where('jenis', 'misi')->first();
        },
        'Galeri (4 terbaru)' => function () {
            return \App\Models\Galeri::latest()->take(4)->get();
        },
        'Pendaftaran aktif' => function () {
            return \App\Models\Pendaftaran::where('is_active', true)->latest()->first();
        },
        'Profil Guru (tampil_di_website)' => function () {
            return \App\Models\Guru::where('tampil_di_website', true)
                ->orderBy('urutan_tampil')
                ->orderBy('nama')
                ->get();
        },
    ];

    $failedQuery = null;

    foreach ($queries as $label => $callback) {
        try {
            $result = $callback();
            $count = is_countable($result) ? count($result) : ($result ? 1 : 0);
            ok("{$label} → OK ({$count} data)");
        } catch (Throwable $e) {
            $failedQuery = $label;
            fail("{$label} → ERROR: " . $e->getMessage());
            echo '<pre>' . htmlspecialchars($e->getTraceAsString()) . '</pre>';
        }
    }

    if ($failedQuery) {
        echo '<br>';
        fail("QUERY YANG GAGAL: <strong>{$failedQuery}</strong> — ini kemungkinan besar penyebab error 500 di halaman beranda!");
    } else {
        echo '<br>';
        ok('Semua query halaman beranda berhasil. Error mungkin di view/blade atau cache.');
    }

    endSection();

    // ═══════════════════════════════════════════════════════════════════
    // 6. TEST RENDER VIEW BERANDA
    // ═══════════════════════════════════════════════════════════════════
    section('6. Test Render View Halaman Beranda');

    try {
        $berita = \App\Models\Berita::where('status', 'publish')->orderBy('published_at', 'desc')->take(3)->get();
        $visi = \App\Models\ProfilSekolah::where('jenis', 'visi')->first();
        $misi = \App\Models\ProfilSekolah::where('jenis', 'misi')->first();
        $galeri = \App\Models\Galeri::latest()->take(4)->get();
        $pendaftaran = \App\Models\Pendaftaran::where('is_active', true)->latest()->first();
        $profil_guru = \App\Models\Guru::where('tampil_di_website', true)->orderBy('urutan_tampil')->orderBy('nama')->get();

        $html = view('pages.home.index', compact('berita', 'visi', 'misi', 'galeri', 'pendaftaran', 'profil_guru'))->render();
        ok('View pages.home.index berhasil di-render (' . strlen($html) . ' bytes)');
    } catch (Throwable $e) {
        fail('GAGAL render view: ' . $e->getMessage());
        echo '<pre>' . htmlspecialchars($e->getTraceAsString()) . '</pre>';
        warn('Jika query OK tapi view gagal, cek file blade atau permission storage/framework/views');
    }

    endSection();
}

// ═══════════════════════════════════════════════════════════════════
// 7. LOG ERROR LARAVEL TERAKHIR
// ═══════════════════════════════════════════════════════════════════
section('7. Log Error Laravel Terakhir (150 baris)');

$logPath = $basePath . '/storage/logs/laravel.log';

if (file_exists($logPath) && is_readable($logPath)) {
    $logSize = filesize($logPath);
    info('Ukuran log: ' . number_format($logSize / 1024, 1) . ' KB');

    $logContent = tailFile($logPath, 150);
    if (trim($logContent) === '') {
        warn('Log kosong - belum ada error tercatat');
    } else {
        echo '<pre>' . htmlspecialchars($logContent) . '</pre>';
    }
} else {
    fail("Log tidak ditemukan atau tidak bisa dibaca: {$logPath}");
}

endSection();

// ═══════════════════════════════════════════════════════════════════
// 8. RINGKASAN & SOLUSI
// ═══════════════════════════════════════════════════════════════════
section('8. Solusi Umum Error 500 di Production');

echo '<div class="info">Jika kolom <strong>gurus.jabatan / tampil_di_website / urutan_tampil</strong> tidak ada:</div>';
echo '<pre>cd ' . htmlspecialchars($basePath) . '
php artisan migrate --force</pre>';

echo '<div class="info">Jika permission error:</div>';
echo '<pre>chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache</pre>';

echo '<div class="info">Jika cache bermasalah:</div>';
echo '<pre>php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear</pre>';

echo '<div class="info">Jika APP_KEY kosong:</div>';
echo '<pre>php artisan key:generate --force</pre>';

warn('Setelah selesai debugging, HAPUS file debug_production.php dari server!');

endSection();
?>

</body>
</html>
