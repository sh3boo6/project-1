<?php

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/State.php';
require_once __DIR__ . '/Router.php';

// Initialize global variables
global $currentUrl, $fullUrl;

// Set environment variables
$_ENV['APP_BASE_URL'] = APP['baseUrl'] ?? 'http://localhost/myphpframework/';

// Set up current URL globals
$GLOBALS['currentUrl'] = current_url();
$GLOBALS['fullUrl'] = full_url();

// Set local variable references as well
$currentUrl = $GLOBALS['currentUrl'];
$fullUrl = $GLOBALS['fullUrl'];

// Store URL information in global state
State::set('currentUrl', $currentUrl);
State::set('fullUrl', $fullUrl);
State::set('baseUrl', APP['baseUrl']);

// Other useful global state values
State::set('requestMethod', $_SERVER['REQUEST_METHOD']);
State::set('isAjax', !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
State::set('requestTime', $_SERVER['REQUEST_TIME']);