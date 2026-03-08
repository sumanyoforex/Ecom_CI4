<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Debug\Toolbar\Collectors\Logs;
use CodeIgniter\Debug\Toolbar\Collectors\Timers;

class Toolbar extends BaseConfig
{
    // Keep only lightweight collectors in development to reduce local overhead.
    public array $collectors = [
        Timers::class,
        Logs::class,
    ];

    public bool $collectVarData = false;
    public int $maxHistory = 5;
    public string $viewsPath = SYSTEMPATH . 'Debug/Toolbar/Views/';
    public int $maxQueries = 20;

    public array $watchedDirectories = [
        'app',
    ];

    public array $watchedExtensions = [
        'php', 'css', 'js', 'html', 'svg', 'json', 'env',
    ];

    public array $disableOnHeaders = [
        'X-Requested-With' => 'xmlhttprequest',
        'HX-Request'       => 'true',
        'X-Up-Version'     => null,
    ];
}
