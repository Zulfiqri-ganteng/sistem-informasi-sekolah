<?php

use CodeIgniter\Boot;
use Config\Paths;

/*
 *---------------------------------------------------------------
 * CHECK PHP VERSION
 *---------------------------------------------------------------
 */

$minPhpVersion = '8.1';
if (version_compare(PHP_VERSION, $minPhpVersion, '<')) {
    echo "PHP version must be {$minPhpVersion} or higher.";
    exit(1);
}

/*
 *---------------------------------------------------------------
 * SET FCPATH
 *---------------------------------------------------------------
 */
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);

/*
 *---------------------------------------------------------------
 * LOAD PATHS
 *---------------------------------------------------------------
 * Folder app/ berada satu level di atas public/
 */
require FCPATH . '../app/Config/Paths.php';

$paths = new Paths();

/*
 *---------------------------------------------------------------
 * BOOT CI4
 *---------------------------------------------------------------
 */
require $paths->systemDirectory . '/Boot.php';

exit(Boot::bootWeb($paths));
