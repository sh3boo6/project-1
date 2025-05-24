<?php

// Helper function to detect the base URL if not set
function detectBaseUrl() {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $scriptPath = dirname($_SERVER['SCRIPT_NAME']);
    $baseUrl = $protocol . '://' . $host . $scriptPath;
    
    // Ensure trailing slash
    if (substr($baseUrl, -1) !== '/') {
        $baseUrl .= '/';
    }
    
    return $baseUrl;
}

define('APP', [
    'name' => 'Project',
    'root' => dirname(dirname(__FILE__)),
    'baseUrl' => detectBaseUrl(),
    'debug' => true,  // Enable debug mode for development
    'version' => '1.0.0',
    'router' => [
        'basePath' => '',  // Set this if the app is in a subdirectory
        'pagesDir' => 'pages',  // Directory for page routes
        'apiDir' => 'api',  // Directory for API routes
        'notFoundPage' => '/pages/404.php',  // Custom 404 page
        'defaultLayout' => 'default',  // Default layout for pages
        'cacheRoutes' => false  // Enable route caching for production
    ],
    'db' => [
        'host' => 'localhost',
        'user' => 'root',
        'pass' => '',
        'name' => 'my_database',
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'prefix' => '',
        'port' => 3306,
    ],
]);