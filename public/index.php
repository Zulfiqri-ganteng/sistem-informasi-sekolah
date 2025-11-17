<?php

use CodeIgniter\Boot;
use Config\Paths;

// ---------------------------------------------------------------
// CEK VERSI PHP
// ---------------------------------------------------------------
$minPhpVersion = '8.1';
if (version_compare(PHP_VERSION, $minPhpVersion, '<')) {
    header('HTTP/1.1 503 Service Unavailable', true, 503);
    exit("PHP minimal {$minPhpVersion}, current: " . PHP_VERSION);
}

// ---------------------------------------------------------------
// SET FCPATH
// ---------------------------------------------------------------
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);

// ---------------------------------------------------------------
// TEMUKAN PATH KE FOLDER APP (OTOMATIS)
// ---------------------------------------------------------------

$possiblePaths = [
    FCPATH . '../app/Config/Paths.php',      // untuk lokal XAMPP
    FCPATH . '../../app/Config/Paths.php',   // untuk shared hosting
    FCPATH . 'app/Config/Paths.php',         // jika CI4 di root sama-level
];

$pathsFile = null;

foreach ($possiblePaths as $file) {
    if (is_file($file)) {
        $pathsFile = $file;
        break;
    }
}

// Jika tetap tidak ditemukan → error
if (!$pathsFile) {
    header('HTTP/1.1 503 Service Unavailable', true, 503);
    exit("Tidak bisa menemukan file Paths.php. Periksa struktur folder.");
}

require $pathsFile;

// ---------------------------------------------------------------
// BOOT CODEIGNITER
// ---------------------------------------------------------------
$paths = new Paths();

require $paths->systemDirectory . '/Boot.php';

exit(Boot::bootWeb($paths));
