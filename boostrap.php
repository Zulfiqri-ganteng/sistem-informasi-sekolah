<?php

/*
 * ---------------------------------------------------------------
 * SETUP OUR PATH CONSTANTS
 * ---------------------------------------------------------------
 *
 * The path constants provide convenient access to the folders
 * throughout the application. We have to setup them up here
 * so they are available in the config files that are loaded.
 */

// Ensure the current directory is pointing to the front controller's directory
chdir(__DIR__);

// Load our paths config file
$paths = new Config\Paths();

// Path to the system directory
define('SYSTEMPATH', $paths->systemDirectory);

// Path to the app directory
define('APPPATH', $paths->appDirectory);

// Path to the writable directory
define('WRITEPATH', $paths->writableDirectory);

// Path to the tests directory
define('TESTPATH', $paths->testsDirectory);

// Load Composer autoloader
if (file_exists($composer = __DIR__ . '/vendor/autoload.php')) {
    require $composer;
} else {
    echo 'ERROR: vendor/autoload.php tidak ditemukan! Jalankan: composer install';
    exit(1);
}

/*
 * ---------------------------------------------------------------
 * GRAB OUR CODEIGNITER INSTANCE
 * ---------------------------------------------------------------
 *
 * The CodeIgniter class contains the core functionality to make
 * the application run, and does all the dirty work to get
 * the pieces all working together.
 */

$app = require SYSTEMPATH . 'bootstrap.php';

/*
 * ---------------------------------------------------------------
 * LAUNCH THE APPLICATION
 * ---------------------------------------------------------------
 * Now that everything is setup, it's time to actually fire
 * up the engines and make this app do its thang.
 */

return $app;