<?php

require __DIR__ . '/vendor/autoload.php';

$dir = __DIR__ . '/vendor/filament/tables/src/Actions';
if (is_dir($dir)) {
    $files = scandir($dir);
    foreach ($files as $file) {
        if (str_ends_with($file, '.php')) {
            echo $file . PHP_EOL;
        }
    }
} else {
    echo "Directory not found: $dir" . PHP_EOL;
}
