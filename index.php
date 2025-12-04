<?php

$publicPath = __DIR__ . '/public';

if (! file_exists($publicPath . '/index.php')) {
    echo "ERROR: public/index.php tidak ditemukan!";
    exit;
}

require $publicPath . '/index.php';
