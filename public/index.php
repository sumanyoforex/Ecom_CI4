<?php

/*
 | -----------------------------------------------------------------------
 | BOOTSTRAP THE APPLICATION
 | -----------------------------------------------------------------------
 | This is the main entry point for every web request.
 | The framework is loaded from the vendor folder.
 */

// Path to the front controller (public/index.php stays clean)
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);

// Load CI4 bootstrap
require FCPATH . 'vendor/codeigniter4/framework/system/bootstrap.php';
