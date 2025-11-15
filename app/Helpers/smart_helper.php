<?php

if (!function_exists('smart_url')) {
    /**
     * Generate URL otomatis sesuai domain (tanpa index.php)
     * Bisa dipakai untuk asset, ajax, upload, dsb.
     */
    function smart_url(string $path = ''): string
    {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $base = rtrim($protocol . $host, '/');

        // Hapus index.php jika masih ada di tengah URL
        $url = $base . '/' . ltrim($path, '/');
        return preg_replace('#/index\.php#', '', $url);
    }
}
