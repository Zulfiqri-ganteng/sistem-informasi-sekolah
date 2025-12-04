<?php
// Path to the front controller (this file)
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);

// Ensure the current directory is pointing to the front controller's directory
chdir(FCPATH);

/*
 *---------------------------------------------------------------
 * BOOTSTRAP THE APPLICATION
 *---------------------------------------------------------------
 * This process sets up the path constants, loads and registers
 * our autoloader, along with Composer's, loads our constants
 * and fires up an environment-specific bootstrapping.
 */

// Path to the project root directory. Just above FCPATH
$pathsPath = realpath(FCPATH . '../app/Config/Paths.php') ?: (realpath(FCPATH . 'app/Config/Paths.php') ?: false);

if (!$pathsPath) {
    header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
    echo 'Your application folder path does not appear to be set correctly.';
    exit(1);
}

define('APPPATH', $pathsPath);
require $pathsPath;

/*
 *---------------------------------------------------------------
 * LAUNCH THE APPLICATION
 *---------------------------------------------------------------
 * Now that everything is set up, it's time to actually fire
 * up the application and make it run!
 */

$app = require APPPATH . '../bootstrap.php';
$app->run();