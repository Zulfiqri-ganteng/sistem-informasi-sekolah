<?php

use CodeIgniter\Boot;
use Config\Paths;

/**
 * KONFIGURASI OTOMATIS LOKAL / HOSTING
 * ====================================================
 * Jika berjalan di hosting, CI otomatis mencari folder
 * di direktori saat ini (public_html/sekolah-galajuara)
 * Jika di lokal, CI otomatis membaca folder project lokal.
 */

$root = dirname(__DIR__);

// Jika di hosting (public_html)
if (is_dir($root . '/app') && is_file($root . '/app/Config/Paths.php')) {
    define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);
    require $root . '/app/Config/Paths.php';
}

// Jika di lokal (xampp / htdocs)
elseif (is_dir($root . '/app') && is_file($root . '/app/Config/Paths.php')) {
    define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);
    require $root . '/app/Config/Paths.php';
}

$paths = new Paths();

require $paths->systemDirectory . '/Boot.php';

exit(Boot::bootWeb($paths));
