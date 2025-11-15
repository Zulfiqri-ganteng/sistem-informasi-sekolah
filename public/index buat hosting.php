<?php

use CodeIgniter\Boot;
use Config\Paths;

/*
 *---------------------------------------------------------------
 * CHECK PHP VERSION
 *---------------------------------------------------------------
 */

$minPhpVersion = '8.1'; // Minimal versi PHP
if (version_compare(PHP_VERSION, $minPhpVersion, '<')) {
    $message = sprintf(
        'Your PHP version must be %s or higher to run CodeIgniter. Current version: %s',
        $minPhpVersion,
        PHP_VERSION,
    );

    header('HTTP/1.1 503 Service Unavailable.', true, 503);
    echo $message;
    exit(1);
}

/*
 *---------------------------------------------------------------
 * SET THE CURRENT DIRECTORY
 *---------------------------------------------------------------
 * Kita arahkan ke folder utama CodeIgniter yang berada di luar public_html
 */
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);

// ðŸ‘‡ arahkan ke lokasi folder utama project CodeIgniter
chdir('/home/zulh7811/tabungan-smk');

/*
 *---------------------------------------------------------------
 * BOOTSTRAP THE APPLICATION
 *---------------------------------------------------------------
 */

// Path ke file konfigurasi utama
$pathsPath = '/home/zulh7811/tabungan-smk/app/Config/Paths.php';

// Pastikan file Paths.php ada
if (!is_file($pathsPath)) {
    header('HTTP/1.1 503 Service Unavailable.', true, 503);
    echo "File Config Paths.php tidak ditemukan! Periksa path: " . htmlspecialchars($pathsPath);
    exit(1);
}

require $pathsPath;

// Inisialisasi Paths
$paths = new Paths();

// Load file Boot utama CodeIgniter
require $paths->systemDirectory . '/Boot.php';

// Jalankan framework
exit(Boot::bootWeb($paths));
