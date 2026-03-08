<?php

/*
 |--------------------------------------------------------------------------
 | ERROR DISPLAY
 |--------------------------------------------------------------------------
 */
error_reporting(E_ALL);
ini_set('display_errors', '1');

/*
 |--------------------------------------------------------------------------
 | DEBUG BACKTRACES
 |--------------------------------------------------------------------------
 */
defined('SHOW_DEBUG_BACKTRACE') || define('SHOW_DEBUG_BACKTRACE', true);

/*
 |--------------------------------------------------------------------------
 | DEBUG MODE
 |--------------------------------------------------------------------------
 */
$ciDebugValue = getenv('CI_DEBUG');
$ciDebug = $ciDebugValue === false ? true : filter_var($ciDebugValue, FILTER_VALIDATE_BOOLEAN);
defined('CI_DEBUG') || define('CI_DEBUG', $ciDebug);
