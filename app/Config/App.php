<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

/*
 | App.php — Core application settings
 | Beginners: Change baseURL to your local server address.
 */
class App extends BaseConfig
{
    // Your site URL (no trailing slash needed)
    public string $baseURL = 'http://localhost:8080/';

    // Default charset for HTML output
    public string $charset = 'UTF-8';

    // Timezone for date/time functions
    public string $appTimezone = 'UTC';

    // ------------------------------------------------------------------
    // Session settings
    // Files are stored in writable/session/
    // ------------------------------------------------------------------
    public string $sessionDriver        = 'CodeIgniter\Session\Handlers\FileSessionHandler';
    public string $sessionCookieName    = 'ci_session';
    public int    $sessionExpiration    = 7200;  // 2 hours
    public string $sessionSavePath      = WRITEPATH . 'session';
    public bool   $sessionMatchIP       = false;
    public int    $sessionTimeToUpdate  = 300;
    public bool   $sessionRegenerateDestroy = false;

    // CSRF protection (protects forms from cross-site attacks)
    public string $CSRFTokenName  = 'csrf_token';
    public string $CSRFCookieName = 'csrf_cookie';
    public int    $CSRFExpire     = 7200;
    public bool   $CSRFRegenerate = true;
    public bool   $CSRFSameTokenFromMultipleTabs = false;
}
