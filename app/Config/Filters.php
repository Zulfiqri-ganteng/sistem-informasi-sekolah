<?php

namespace Config;

use CodeIgniter\Config\Filters as BaseFilters;
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\Filters\Honeypot;
use CodeIgniter\Filters\InvalidChars;
use CodeIgniter\Filters\SecureHeaders;
use CodeIgniter\Filters\Cors;
use CodeIgniter\Filters\PageCache;
use CodeIgniter\Filters\PerformanceMetrics;

// Custom Filters
use App\Filters\AuthFilter;
use App\Filters\RoleFilter;
use App\Filters\RemoveIndex;

class Filters extends BaseFilters
{
    /**
     * Alias filter untuk memudahkan pemanggilan.
     */
    public array $aliases = [
        'csrf'          => CSRF::class,
        'toolbar'       => DebugToolbar::class,
        'honeypot'      => Honeypot::class,
        'invalidchars'  => InvalidChars::class,
        'secureheaders' => SecureHeaders::class,
        'cors'          => Cors::class,
        'pagecache'     => PageCache::class,
        'performance'   => PerformanceMetrics::class,

        // Custom filters
        'auth'          => AuthFilter::class,
        'role'          => RoleFilter::class,
        'removeindex'   => RemoveIndex::class,
        'admin' => \App\Filters\RoleFilter::class,
        'absensiRole' => \App\Filters\AbsensiRoleFilter::class,
    ];

    /**
     * Filter global yang aktif otomatis untuk semua request.
     */
    public array $globals = [
        'before' => [
            'removeindex',
            // 'csrf', // aktifkan CSRF global
        ],
        'after' => [
            
        ],
    ];

    /**
     * Filter berdasarkan metode HTTP
     */
    public array $methods = [];

    /**
     * Filter berdasarkan route (URI)
     */
    public array $filters = [
        'auth' => [
            'before' => [
                'dashboard',
                'dashboard/*',
                'siswa',
                'siswa/*',
                'kelas',
                'kelas/*',
                'guru',
                'guru/*',
                'jurusan',
                'jurusan/*',
                'tabungan',
                'tabungan/*',
                'laporan',
                'laporan/*',
                'mapel',
                'mapel/*',
            ],
            'except' => [
                'login',
                'logout',
                'auth/*',
            ],
        ],

        // 🔒 Pastikan role filter juga dikenali oleh CI
        'role' => [
            'before' => [
                'guru',
                'guru/*',
                'siswa',
                'siswa/*',
            ],
        ],
    ];

    /**
     * Required filters (dibiarkan kosong agar tidak error "forcehttps")
     */
    public array $required = [
        'before' => [],
        'after'  => [],
    ];
}
