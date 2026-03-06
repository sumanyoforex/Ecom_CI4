<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

/*
 | Filters.php — Register custom filters (middleware) here.
 |
 | 'auth'  → verifies JWT token or session for regular users
 | 'admin' → verifies admin session for admin panel
 */
class Filters extends BaseConfig
{
    // Map alias names to their filter classes
    public array $aliases = [
        'csrf'  => \CodeIgniter\Filters\CSRF::class,
        'auth'  => \App\Filters\AuthFilter::class,
        'admin' => \App\Filters\AdminFilter::class,
    ];

    // Filters that run on every request
    public array $globals = [
        'before' => [
            'csrf' => ['except' => ['api/*']],
        ],
        'after'  => [],
    ];

    // Method-based filters (not used here)
    public array $methods = [];

    // Route-based filters (defined inline in Routes.php instead)
    public array $filters = [];
}
