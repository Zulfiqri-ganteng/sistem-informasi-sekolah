<?php

$root = realpath(__DIR__ . '/..');

require $root . '/vendor/autoload.php';

$app = require $root . '/app/Config/Bootstrap.php';

$app->run();
