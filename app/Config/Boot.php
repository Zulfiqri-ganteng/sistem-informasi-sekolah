<?php

/**
 * This file is part of CodeIgniter 4 framework.
 *
 * (c) CodeIgniter Foundation <admin@codeigniter.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

/*
 | --------------------------------------------------------------------
 | Composer Auto-Loader
 | --------------------------------------------------------------------
 | Composer provides a convenient, automatically generated class loader
 | for our application. We just need to utilize it! We'll require it
 | into the script here so we don't have to manually load any of
 | our application's PHP classes. It just feels great to relax.
 */

require_once ROOTPATH . 'vendor/autoload.php';

/*
 | --------------------------------------------------------------------
 | Load Framework Services
 | --------------------------------------------------------------------
 */

$services = SERVICES_PATH . 'Services.php';

if (! file_exists($services)) {
    die('Services file not found. Please run `php spark setup`.');
}

require $services;

/*
 | --------------------------------------------------------------------
 | Set Environment
 | --------------------------------------------------------------------
 */

require_once SYSTEMPATH . 'Config/DotEnv.php';

$env = new CodeIgniter\Config\DotEnv(ROOTPATH);
$env->load();

define('ENVIRONMENT', $_SERVER['CI_ENVIRONMENT'] ?? 'production');

/*
 | --------------------------------------------------------------------
 | Load Framework Constants
 | --------------------------------------------------------------------
 */

require_once SYSTEMPATH . 'Config/Constants.php';

if (file_exists(APPPATH . 'Config/Constants.php')) {
    require_once APPPATH . 'Config/Constants.php';
}

/*
 | --------------------------------------------------------------------
 | Setup Custom Error Handling
 | --------------------------------------------------------------------
 */

require_once SYSTEMPATH . 'Boot.php';
