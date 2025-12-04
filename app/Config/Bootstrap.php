<?php

/**
 * This file is part of CodeIgniter 4 framework.
 *
 * (c) CodeIgniter Foundation <admin@codeigniter.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

use CodeIgniter\Config\Factories;
use Config\Services;

/*
 | --------------------------------------------------------------------
 | Application Bootstrap
 | --------------------------------------------------------------------
 */

// Create new instance of our application
$app = Config\Services::app(null, true);

/*
 | --------------------------------------------------------------------
 | Run The Application
 | --------------------------------------------------------------------
 */

// Run the application
$app->run();

/*
 | --------------------------------------------------------------------
 | Shutdown the application
 | --------------------------------------------------------------------
 */

// Clean up
Factories::reset();
Services::reset();
