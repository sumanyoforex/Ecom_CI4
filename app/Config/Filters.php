<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Filters extends BaseConfig
{
    public array $aliases = [
        'csrf' => \CodeIgniter\Filters\CSRF::class,
        'auth' => \App\Filters\AuthFilter::class,
        'admin' => \App\Filters\AdminFilter::class,
        'securityheaders' => \App\Filters\SecurityHeadersFilter::class,
    ];

    public array $globals = [
        'before' => [
            'csrf' => ['except' => ['api/*', 'webhooks/*']],
        ],
        'after' => [
            'securityheaders',
        ],
    ];

    public array $methods = [];
    public array $filters = [];
}

