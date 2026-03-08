<?php

namespace Config;

use CodeIgniter\Database\Config;

/*
 | Database.php — tells CI4 which database to use.
 | We use SQLite3 — a single file database (no server needed).
 */
class Database extends Config
{
    public string $defaultGroup = 'default';

    public array $default = [
        'DBDriver' => 'SQLite3',
        // Database file lives at writable/database.sqlite
        'database' => WRITEPATH . 'database.sqlite',
        'DBPrefix' => '',
        'DBDebug'  => ENVIRONMENT !== 'production',
        'charset'  => 'utf8',
        'DBCollat' => 'utf8_general_ci',
    ];

    public array $tests = [
        'DBDriver' => 'SQLite3',
        'database' => WRITEPATH . 'database_test.sqlite',
    ];
}

