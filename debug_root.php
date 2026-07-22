<?php
/**
 * DEBUG ROOT - Upload ke folder yang SAMA dengan index.php luar
 * Akses: https://www.sdnegeri1passo.site/debug_root.php?key=tes123
 * HAPUS setelah selesai!
 */

// Tangkap semua error agar tidak jadi 500 kosong
error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');

register_shutdown_function(function () {
    $err = error_get_last();
    if ($err && in_array($err['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR], true)) {
        echo "\n\n[FATAL SHUTDOWN] {$err['message']}\n";
        echo "File: {$err['file']}:{$err['line']}\n";
    }
});

// Kunci keamanan
if (($_GET['key'] ?? '') !== 'tes123') {
    http_response_code(403);
    die('403 - gunakan ?key=tes123');
}

header('Content-Type: text/plain; charset=utf-8');

echo "=== DEBUG ROOT LARAVEL ===\n";
echo 'Waktu: ' . date('Y-m-d H:i:s T') . "\n";
echo 'PHP: ' . PHP_VERSION . "\n";
echo 'File ini: ' . __FILE__ . "\n";
echo 'Folder ini: ' . __DIR__ . "\n\n";

// Cari root Laravel (folder yang punya vendor + bootstrap)
$candidates = [
    __DIR__,
    __DIR__ . '/..',
    dirname(__DIR__),
];

$root = null;
foreach ($candidates as $dir) {
    $real = realpath($dir);
    if (!$real) {
        continue;
    }
    if (is_file($real . '/vendor/autoload.php') && is_file($real . '/bootstrap/app.php')) {
        $root = $real;
        break;
    }
}

echo "=== CARI ROOT LARAVEL ===\n";
foreach ($candidates as $dir) {
    $real = realpath($dir) ?: $dir;
    $ok = is_file($real . '/vendor/autoload.php');
    echo ($ok ? '[OK] ' : '[NO] ') . $real . "\n";
}

if (!$root) {
    echo "\n[ERROR] Root Laravel tidak ditemukan!\n";
    echo "Pastikan file ini diletakkan di folder yang sama dengan:\n";
    echo "  - vendor/autoload.php\n";
    echo "  - bootstrap/app.php\n";
    echo "  - index.php (yang Anda pakai di luar public)\n";
    exit;
}

echo "\nRoot Laravel: {$root}\n\n";

// Cek file penting
echo "=== CEK FILE ===\n";
$files = [
    'index.php',
    'vendor/autoload.php',
    'bootstrap/app.php',
    '.env',
    'artisan',
    'storage/logs/laravel.log',
];

foreach ($files as $f) {
    $path = $root . '/' . $f;
    echo (file_exists($path) ? '[OK] ' : '[NO] ') . $f;
    if (file_exists($path) && is_file($path)) {
        echo ' (' . round(filesize($path) / 1024, 1) . ' KB)';
    }
    echo "\n";
}

// Cek folder writable
echo "\n=== CEK FOLDER WRITABLE ===\n";
$dirs = ['storage', 'storage/framework', 'storage/framework/views', 'storage/logs', 'bootstrap/cache'];
foreach ($dirs as $d) {
    $path = $root . '/' . $d;
    if (!is_dir($path)) {
        echo "[NO] {$d} - folder tidak ada\n";
    } elseif (!is_writable($path)) {
        echo "[NO] {$d} - tidak bisa ditulis (chmod 775)\n";
    } else {
        echo "[OK] {$d}\n";
    }
}

// Bootstrap Laravel
echo "\n=== BOOTSTRAP LARAVEL ===\n";
try {
    require $root . '/vendor/autoload.php';
    echo "[OK] autoload loaded\n";

    $app = require $root . '/bootstrap/app.php';
    echo "[OK] app loaded\n";

    $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
    echo "[OK] kernel booted\n";
    echo 'Environment: ' . app()->environment() . "\n";
    echo 'Debug: ' . (config('app.debug') ? 'true' : 'false') . "\n";
    echo 'URL: ' . config('app.url') . "\n";

} catch (Throwable $e) {
    echo "[ERROR] Bootstrap gagal:\n";
    echo $e->getMessage() . "\n\n";
    echo $e->getTraceAsString() . "\n";
    exit;
}

// Test database
echo "\n=== TEST DATABASE ===\n";
try {
    Illuminate\Support\Facades\DB::connection()->getPdo();
    echo "[OK] Koneksi database berhasil\n";
} catch (Throwable $e) {
    echo '[ERROR] DB: ' . $e->getMessage() . "\n";
}

// Test query halaman user (HomeController)
echo "\n=== TEST QUERY HALAMAN USER ===\n";
$tests = [
    'Berita publish' => function () {
        return App\Models\Berita::where('status', 'publish')->orderBy('published_at', 'desc')->take(3)->get();
    },
    'Visi' => function () {
        return App\Models\ProfilSekolah::where('jenis', 'visi')->first();
    },
    'Misi' => function () {
        return App\Models\ProfilSekolah::where('jenis', 'misi')->first();
    },
    'Galeri' => function () {
        return App\Models\Galeri::latest()->take(4)->get();
    },
    'Pendaftaran aktif' => function () {
        return App\Models\Pendaftaran::where('is_active', true)->latest()->first();
    },
    'Profil Guru' => function () {
        $q = App\Models\Guru::query();
        if (Illuminate\Support\Facades\Schema::hasColumn('gurus', 'tampil_di_website')) {
            $q->where('tampil_di_website', true);
        }
        if (Illuminate\Support\Facades\Schema::hasColumn('gurus', 'urutan_tampil')) {
            $q->orderBy('urutan_tampil');
        }
        return $q->orderBy('nama')->get();
    },
];

$failed = null;
foreach ($tests as $label => $fn) {
    try {
        $result = $fn();
        $count = is_countable($result) ? count($result) : ($result ? 1 : 0);
        echo "[OK] {$label} ({$count} data)\n";
    } catch (Throwable $e) {
        $failed = $label;
        echo "[ERROR] {$label}: " . $e->getMessage() . "\n";
    }
}

// Test render view beranda
echo "\n=== TEST RENDER VIEW BERANDA ===\n";
try {
    $berita = App\Models\Berita::where('status', 'publish')->orderBy('published_at', 'desc')->take(3)->get();
    $visi = App\Models\ProfilSekolah::where('jenis', 'visi')->first();
    $misi = App\Models\ProfilSekolah::where('jenis', 'misi')->first();
    $galeri = App\Models\Galeri::latest()->take(4)->get();
    $pendaftaran = App\Models\Pendaftaran::where('is_active', true)->latest()->first();
    $profil_guru = App\Models\Guru::orderBy('nama')->get();

    $html = view('pages.home.index', compact('berita', 'visi', 'misi', 'galeri', 'pendaftaran', 'profil_guru'))->render();
    echo '[OK] View berhasil di-render (' . strlen($html) . " bytes)\n";
} catch (Throwable $e) {
    echo '[ERROR] Render view gagal: ' . $e->getMessage() . "\n\n";
    echo $e->getTraceAsString() . "\n";
}

// Test simulasi route /
echo "\n=== TEST SIMULASI ROUTE / ===\n";
try {
    $request = Illuminate\Http\Request::create('/', 'GET');
    $response = app()->handle($request);
    echo '[OK] Route / status: ' . $response->getStatusCode() . "\n";
    if ($response->getStatusCode() >= 400) {
        echo "Body (500 char pertama):\n";
        echo substr($response->getContent(), 0, 500) . "\n";
    }
} catch (Throwable $e) {
    echo '[ERROR] Route / gagal: ' . $e->getMessage() . "\n\n";
    echo $e->getTraceAsString() . "\n";
}

// Log terakhir
echo "\n=== LOG ERROR TERAKHIR ===\n";
$logPath = $root . '/storage/logs/laravel.log';
if (is_readable($logPath)) {
    $lines = file($logPath);
    if ($lines) {
        echo implode('', array_slice($lines, -60));
    } else {
        echo "(log kosong)\n";
    }
} else {
    echo "Tidak bisa baca: {$logPath}\n";
}

echo "\n\n=== SELESAI - HAPUS FILE debug_root.php ===\n";
