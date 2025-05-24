<?php

function layout($name, $data = []) {
    extract($data);
    include APP['root'] . "/layouts/{$name}.php";
}

/**
 * Get a parameter from the current route
 */
function param($name, $default = null) {
    global $router;
    return $router->getParam($name) ?? $default;
}

/**
 * Get all parameters from the current route
 */
function params() {
    global $router;
    return $router->getParams();
}

/**
 * Get the current URL (path) without query string
 */
function current_url() {
    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    
    // Get script directory for XAMPP
    $scriptDir = dirname($_SERVER['SCRIPT_NAME']);
    $basePath = ($scriptDir === '/' || $scriptDir === '\\') ? '' : $scriptDir;
    
    // Remove the basePath from the current URL if it exists
    if ($basePath && strpos($path, $basePath) === 0) {
        $path = substr($path, strlen($basePath));
    }
    
    // Ensure the path starts with a slash
    if (empty($path) || $path[0] !== '/') {
        $path = '/' . $path;
    }
    
    return $path;
}

/**
 * Get the full current URL including protocol, host, port, path and query string
 */
function full_url() {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    $uri = $_SERVER['REQUEST_URI'];
    
    return "$protocol://$host$uri";
}

/**
 * Access global state - a shorter way to use the State class
 * 
 * @param string|null $key The key to retrieve or null to get all state
 * @param mixed $default The default value to return if the key doesn't exist
 * @return mixed The value from the state or all values
 */
function state($key = null, $default = null) {
    if ($key === null) {
        return State::all();
    }
    return State::get($key, $default);
}

/**
 * Get the current URL path globally
 * Returns consistent results through multiple access methods
 */
function get_current_url() {
    // First try from GLOBALS
    if (isset($GLOBALS['currentUrl'])) {
        return $GLOBALS['currentUrl'];
    }
    
    // Then try from State
    if (State::has('currentUrl')) {
        return State::get('currentUrl');
    }
    
    // Finally calculate it directly
    return current_url();
}

/**
 * Get the full URL globally
 * Returns consistent results through multiple access methods
 */
function get_full_url() {
    // First try from GLOBALS
    if (isset($GLOBALS['fullUrl'])) {
        return $GLOBALS['fullUrl'];
    }
    
    // Then try from State
    if (State::has('fullUrl')) {
        return State::get('fullUrl');
    }
    
    // Finally calculate it directly
    return full_url();
}

/**
 * Generate a URL to a named route
 */
function url($path = '', $params = []) {
    // Build URL based on base URL and path
    $baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . 
               "://" . $_SERVER['HTTP_HOST'];
    
    // Get the script directory (for XAMPP)
    $scriptDir = dirname($_SERVER['SCRIPT_NAME']);
    $basePath = ($scriptDir === '/' || $scriptDir === '\\') ? '' : $scriptDir;
    
    // Format the path correctly
    $path = '/' . ltrim($path, '/');
    
    // Replace parameter placeholders
    if (!empty($params)) {
        foreach ($params as $key => $value) {
            $path = str_replace("[$key]", $value, $path);
        }
    }
    
    return $baseUrl . $basePath . $path;
}

/**
 * Generate a URL to a route by path name
 * This function creates proper links to routes in your application
 * 
 * @param string $path The route path (without leading slash)
 * @param array $params Optional parameters for dynamic routes
 * @param bool $absolute Whether to return an absolute URL (with domain)
 * @return string The URL to the route
 */
function route($path = '', $params = [], $absolute = false) {
    // Get base URL from config
    $baseUrl = rtrim(APP['baseUrl'] ?? '', '/');
    
    // Get script directory for subdirectory installations
    $scriptDir = dirname($_SERVER['SCRIPT_NAME']);
    $basePath = ($scriptDir === '/' || $scriptDir === '\\') ? '' : $scriptDir;
    
    // Clean up the path
    $path = ltrim($path, '/');
    
    // Handle empty path (home page)
    if (empty($path)) {
        return $absolute ? $baseUrl : $basePath;
    }
    
    // Replace parameter placeholders for dynamic routes
    if (!empty($params)) {
        foreach ($params as $key => $value) {
            // Replace both [param] and :param formats
            $path = str_replace("[$key]", $value, $path);
            $path = preg_replace('/\:' . $key . '(?=[\/\?]|$)/', $value, $path);
        }
    }
    
    // Return either absolute or relative URL
    return $absolute ? $baseUrl . '/' . $path : $basePath . '/' . $path;
}

/**
 * Redirect to a URL
 */
function redirect($path, $params = []) {
    $url = route($path, $params, true); // Use route() to generate the URL with proper base path handling
    header("Location: $url");
    exit;
}

/**
 * Get the current request method (GET, POST, etc.)
 */
function request_method() {
    return $_SERVER['REQUEST_METHOD'];
}

/**
 * Check if the request is an AJAX request
 */
function is_ajax() {
    return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
           strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}

/**
 * Get a value from the request (GET or POST)
 */
function request($key, $default = null) {
    if (isset($_POST[$key])) {
        return $_POST[$key];
    }
    
    if (isset($_GET[$key])) {
        return $_GET[$key];
    }
    
    return $default;
}

/**
 * Send a JSON response and exit
 */
function json_response($data, $status = 200) {
    http_response_code($status);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

/**
 * Sanitize output to prevent XSS
 */
function e($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

/**
 * Generate a URL to an asset (CSS, JS, images, etc.)
 * This function creates proper links to assets in your application
 * 
 * @param string $path The asset path relative to the assets folder
 * @param bool $absolute Whether to return an absolute URL (with domain)
 * @param string $version Optional version parameter for cache busting
 * @return string The URL to the asset
 */
function asset($path = '', $absolute = false, $version = null) {
    // Get base URL from config
    $baseUrl = rtrim(APP['baseUrl'] ?? '', '/');
    
    // Get script directory for subdirectory installations
    $scriptDir = dirname($_SERVER['SCRIPT_NAME']);
    $basePath = ($scriptDir === '/' || $scriptDir === '\\') ? '' : $scriptDir;
    
    // Clean up the path and ensure it doesn't start with slash
    $path = ltrim($path, '/');
    
    // Build the asset path
    $assetPath = 'assets/' . $path;
    
    // Add version parameter for cache busting if provided
    if ($version !== null) {
        $separator = strpos($assetPath, '?') !== false ? '&' : '?';
        $assetPath .= $separator . 'v=' . $version;
    }
    
    // Return either absolute or relative URL
    if ($absolute) {
        return $baseUrl . '/' . $assetPath;
    } else {
        return $basePath . '/' . $assetPath;
    }
}

/**
 * Generate a URL to a CSS file in the assets folder
 * 
 * @param string $filename The CSS filename (with or without .css extension)
 * @param bool $absolute Whether to return an absolute URL
 * @param string $version Optional version for cache busting
 * @return string The URL to the CSS file
 */
function css($filename, $absolute = false, $version = null) {
    // Add .css extension if not present
    if (!str_ends_with($filename, '.css')) {
        $filename .= '.css';
    }
    
    return asset('css/' . $filename, $absolute, $version);
}

/**
 * Generate a URL to a JavaScript file in the assets folder
 * 
 * @param string $filename The JS filename (with or without .js extension)
 * @param bool $absolute Whether to return an absolute URL
 * @param string $version Optional version for cache busting
 * @return string The URL to the JS file
 */
function js($filename, $absolute = false, $version = null) {
    // Add .js extension if not present
    if (!str_ends_with($filename, '.js')) {
        $filename .= '.js';
    }
    
    return asset('js/' . $filename, $absolute, $version);
}

/**
 * Generate a URL to an image file in the assets folder
 * 
 * @param string $filename The image filename
 * @param bool $absolute Whether to return an absolute URL
 * @param string $version Optional version for cache busting
 * @return string The URL to the image file
 */
function img($filename, $absolute = false, $version = null) {
    return asset('img/' . $filename, $absolute, $version);
}

/**
 * Generate a URL to a font file in the assets folder
 * 
 * @param string $filename The font filename
 * @param bool $absolute Whether to return an absolute URL
 * @param string $version Optional version for cache busting
 * @return string The URL to the font file
 */
function font($filename, $absolute = false, $version = null) {
    return asset('font/' . $filename, $absolute, $version);
}

/**
 * Connect to the database using PDO
 * 
 * @return PDO The PDO instance for database connection
 * @throws PDOException If the connection fails
 */
function db_connect() {
    // Database configuration
    $host = APP['db']['host'] ?? '127.0.0.1';
    $dbname = APP['db']['name'] ?? 'myphpframework';
    $username = APP['db']['user'] ?? 'root';
    $password = APP['db']['pass'] ?? '';
    $charset = APP['db']['charset'] ?? 'utf8mb4';

    // Data Source Name (DSN)
    $port = APP['db']['port'] ?? 3306;
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=$charset";

    try {
        // Create a new PDO instance
        $pdo = new PDO($dsn, $username, $password);
        // Set error mode to exception
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        // Handle connection error
        throw new PDOException("Database connection failed: " . $e->getMessage());
    }
}

/**
 * Abort the request with an error page
 * 
 * @param int $status The HTTP status code (e.g. 403 for Forbidden, 404 for Not Found)
 * @param string $message The error message to display
 * @return void This function will terminate script execution
 */
function abort($status = 403, $message = null) {
    // Set default messages based on status code if not provided
    if ($message === null) {
        switch ($status) {
            case 400:
                $message = 'Bad request. The server cannot process the request due to client error.';
                break;
            case 401:
                $message = 'Unauthorized. Authentication is required to access this resource.';
                break;
            case 403:
                $message = 'Forbidden. You do not have permission to access this resource.';
                break;
            case 404:
                $message = 'The requested resource could not be found.';
                break;
            case 419:
                $message = 'Your session has expired. Please refresh and try again.';
                break;
            case 429:
                $message = 'Too many requests. Please try again later.';
                break;
            case 500:
                $message = 'Internal server error. Something went wrong on our end.';
                break;
            case 503:
                $message = 'Service unavailable. The server is temporarily unable to handle the request.';
                break;
            default:
                $message = 'An error occurred while processing your request.';
        }
    }
    
    // Set HTTP status code
    http_response_code($status);
    
    // For API requests, return JSON
    if (strpos($_SERVER['REQUEST_URI'], '/api/') === 0 || 
        (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false) ||
        (isset($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false)) {
        
        json_response([
            'error' => true,
            'status' => $status,
            'message' => $message
        ], $status);
    }
    
    // Extract any additional data passed to the function
    $data = ['status' => $status, 'message' => $message];
    
    // For regular web requests, show the error page
    extract($data);
    include APP['root'] . '/pages/error.php';
    exit;
}

function readFromUploadsAsBase64($filePath) {

    $filePath = APP['root'] . '/src/uploads/' . ltrim($filePath, '/');
    if (!file_exists($filePath)) {
        return null;
    }
    
    $fileContent = file_get_contents($filePath);
    if ($fileContent === false) {
        return null;
    }
    
    // Encode the file content to base64
    //return base64_encode($fileContent);

    return [
        'filePath' => $filePath,
        'base64' => base64_encode($fileContent),
        'type' => mime_content_type($filePath),
        'size' => round(filesize($filePath) / 1024, 2) . ' KB',
        'name' => basename($filePath),
        'url' => url('uploads/' . ltrim($filePath, '/'), [], true) // Generate absolute URL
    ];
}
