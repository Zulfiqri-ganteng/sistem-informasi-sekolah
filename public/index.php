<?php

use CodeIgniter\Boot;
use Config\Paths;

/*
 * CHECK PHP VERSION
 */

$minPhpVersion = '8.1';
if (version_compare(PHP_VERSION, $minPhpVersion, '<')) {
    die("PHP version must be {$minPhpVersion} or higher");
}

/*
 * FRONT CONTROLLER
 */
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);

/*
 * DETEKSI MODE (LOKAL / HOSTING)
 */
$isLocal = (php_sapi_name() === 'cli-server' || strpos($_SERVER['HTTP_HOST'] ?? '', 'localhost') !== false);

/*
 * SET ROOT PROJECT
 *
 * Lokal:
 *   project ada di ../ (default CI4)
 *
 * Hosting:
 *   mas menaruh project langsung di:
 *   /home/zulh7811/public_html/sekolah-galajuara.zulfiqri.com/
 */
if ($isLocal) {
    // LOKAL
    $rootPath = __DIR__ . '/../';
} else {
    // HOSTING
    $rootPath = __DIR__ . '/';
}

chdir($rootPath);

/*
 * LOAD PATHS CONFIG
 */
require $rootPath . 'app/Config/Paths.php';
$paths = new Paths();

/*
 * BOOTSTRAP
 */
require $paths->systemDirectory . '/Boot.php';

exit(Boot::bootWeb($paths));
