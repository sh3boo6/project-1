<?php

class Router {
    private $routes = [];
    private $params = [];
    private $currentRoute = null;
    private $basePath = '';
    private $middlewares = [];
    private $config = [];

    public function __construct($basePath = '') {
        // Get router config from APP constant
        $this->config = APP['router'] ?? [];
        
        // Set base path from config or parameter
        $this->basePath = $basePath ?: ($this->config['basePath'] ?? '');
        
        // Get pages directory from config
        $pagesDir = $this->config['pagesDir'] ?? 'pages';
        $apiDir = $this->config['apiDir'] ?? 'api';
        
        // Scan pages directory to build routes
        if (is_dir(APP['root'] . "/$pagesDir")) {
            $this->scanDirectory(APP['root'] . "/$pagesDir");
        }
        
        // Also scan the API directory if it exists
        if (is_dir(APP['root'] . "/$apiDir")) {
            $this->scanApiDirectory(APP['root'] . "/$apiDir");
        }
    }

    /**
     * Scan the pages directory to automatically build routes for web pages
     */
    private function scanDirectory($directory, $baseRoute = '', $currentParams = []) {
        $files = scandir($directory);
        
        // Separate files by type for better routing control
        $directories = [];
        $indexFiles = [];
        $staticFiles = [];  // Regular files like about.php, contact.php
        $dynamicFiles = []; // Dynamic parameter files like [id].php
        $dynamicDirs = [];  // Dynamic directories like [name]/
        
        foreach ($files as $file) {
            if ($file === '.' || $file === '..') continue;
            
            $path = $directory . '/' . $file;
            
            if (is_dir($path)) {
                // Check if directory name is a dynamic parameter
                if (strpos($file, '[') === 0 && strpos($file, ']') !== false) {
                    $dynamicDirs[] = $file;
                } else {
                    $directories[] = $file;
                }
            } else {
                $fileName = pathinfo($file, PATHINFO_FILENAME);
                $extension = pathinfo($file, PATHINFO_EXTENSION);
                
                // Only process PHP files
                if ($extension !== 'php') continue;
                
                if ($fileName === 'index') {
                    $indexFiles[] = $file;
                } else if (strpos($fileName, '[') === 0 && strpos($fileName, ']') !== false) {
                    $dynamicFiles[] = $file;
                } else {
                    $staticFiles[] = $file;
                }
            }
        }
        
        // Process regular directories first
        foreach ($directories as $file) {
            $path = $directory . '/' . $file;
            $route = $baseRoute;
            $dirRoute = $file;
            $this->scanDirectory($path, $route . '/' . $dirRoute, $currentParams);
        }
        
        // Process dynamic directories with nested parameters
        foreach ($dynamicDirs as $file) {
            $path = $directory . '/' . $file;
            $paramName = trim($file, '[]');
            $newParams = array_merge($currentParams, [$paramName]);
            $paramRoute = $baseRoute . '/(:any)';
            $this->scanDirectory($path, $paramRoute, $newParams);
        }
        
        // Process index files next
        foreach ($indexFiles as $file) {
            $path = $directory . '/' . $file;
            $route = $baseRoute;
            $this->addRoute($route, $path, $currentParams);
        }
        
        // Process static files before dynamic ones to ensure they have priority
        foreach ($staticFiles as $file) {
            $path = $directory . '/' . $file;
            $route = $baseRoute;
            $fileName = pathinfo($file, PATHINFO_FILENAME);
            $this->addRoute($route . '/' . $fileName, $path, $currentParams);
        }
        
        // Process dynamic files last so they don't override static routes
        foreach ($dynamicFiles as $file) {
            $path = $directory . '/' . $file;
            $route = $baseRoute;
            $fileName = pathinfo($file, PATHINFO_FILENAME);
            $paramName = trim($fileName, '[]');
            $allParams = array_merge($currentParams, [$paramName]);
            $paramRoute = $route . '/(:any)';
            $this->addRoute($paramRoute, $path, $allParams);
        }
    }
    
    /**
     * Scan the API directory to automatically build API routes
     */
    private function scanApiDirectory($directory, $baseRoute = '/api') {
        $files = scandir($directory);
        
        // Separate files and directories, then sort to ensure static routes are registered before dynamic ones
        $directories = [];
        $staticFiles = [];
        $dynamicFiles = [];
        
        foreach ($files as $file) {
            if ($file === '.' || $file === '..') continue;
            
            $path = $directory . '/' . $file;
            
            if (is_dir($path)) {
                $directories[] = $file;
            } else {
                $fileName = pathinfo($file, PATHINFO_FILENAME);
                $extension = pathinfo($file, PATHINFO_EXTENSION);
                
                // Only process PHP files
                if ($extension !== 'php') continue;
                
                // Check if this is a dynamic route (has square brackets)
                if (strpos($fileName, '[') === 0 && strpos($fileName, ']') !== false) {
                    $dynamicFiles[] = $file;
                } else {
                    $staticFiles[] = $file;
                }
            }
        }
        
        // Sort arrays to ensure consistent ordering
        sort($directories);
        sort($staticFiles);
        sort($dynamicFiles);
        
        // Process directories first (for nested routes)
        foreach ($directories as $file) {
            $path = $directory . '/' . $file;
            $dirRoute = $file;
            $this->scanApiDirectory($path, $baseRoute . '/' . $dirRoute);
        }
        
        // Process static files first to ensure they take precedence
        foreach ($staticFiles as $file) {
            $path = $directory . '/' . $file;
            $fileName = pathinfo($file, PATHINFO_FILENAME);
            
            if ($fileName === 'index') {
                // Index files map to the directory path
                $this->addRoute($baseRoute, $path, [], 'API');
            } else {
                // Regular file
                $this->addRoute($baseRoute . '/' . $fileName, $path, [], 'API');
                
                // Also add a dynamic route version to handle REST API style paths
                // e.g., /api/users/single.php also becomes /api/users/single/(:any) for /api/users/single/1
                if ($fileName === 'single') {
                    $paramRoute = $baseRoute . '/' . $fileName . '/(:any)';
                    $this->addRoute($paramRoute, $path, ['id'], 'API');
                }
            }
        }
        
        // Process dynamic files last so they don't override static routes
        foreach ($dynamicFiles as $file) {
            $path = $directory . '/' . $file;
            $fileName = pathinfo($file, PATHINFO_FILENAME);
            
            // Dynamic parameter route like [id].php
            $paramName = trim($fileName, '[]');
            $paramRoute = $baseRoute . '/(:any)';
            $this->addRoute($paramRoute, $path, [$paramName], 'API');
        }
    }

    /**
     * Add a route to the routes array
     */
    private function addRoute($route, $filePath, $params = [], $type = 'PAGE') {
        // Normalize route path
        $route = $this->normalizeRoute($route);
        
        // Convert route with parameters to regex pattern
        $pattern = $this->routeToRegex($route);
        
        $this->routes[$pattern] = [
            'file' => $filePath,
            'params' => $params,
            'type' => $type
        ];
    }
    
    /**
     * Add middleware to be executed before route handling
     */
    public function addMiddleware($callback) {
        $this->middlewares[] = $callback;
        return $this;
    }

    /**
     * Normalize a route to ensure consistent format
     */
    private function normalizeRoute($route) {
        // Replace multiple slashes with a single slash
        $route = preg_replace('#/+#', '/', $route);
        // Remove trailing slash
        $route = rtrim($route, '/');
        
        return $route ?: '/';
    }

    /**
     * Convert route pattern to regex for matching
     */
    private function routeToRegex($route) {
        $route = str_replace('/', '\/', $route);
        $route = preg_replace('/\(:any\)/', '([^\/]+)', $route);
        $route = preg_replace('/\(:num\)/', '([0-9]+)', $route);
        $route = preg_replace('/\(:alpha\)/', '([a-zA-Z]+)', $route);
        $route = preg_replace('/\(:alphanum\)/', '([a-zA-Z0-9]+)', $route);
        
        // Make trailing slash optional by using \/?$ instead of $ at the end
        return '/^' . $route . '\/?$/';
    }

    /**
     * Match current URL to a route
     */
    public function match() {
        $url = $this->getCurrentUrl();
        $method = $_SERVER['REQUEST_METHOD'];
        
        // Debug information in case debug mode is enabled
        $debugInfo = [];
        
        foreach ($this->routes as $pattern => $routeData) {
            // Store match attempt for debugging
            $matched = preg_match($pattern, $url, $matches);
            if (APP['debug'] ?? false) {
                $debugInfo[] = [
                    'pattern' => $pattern,
                    'url' => $url,
                    'file' => $routeData['file'],
                    'matched' => $matched ? 'YES' : 'no'
                ];
            }
            
            if ($matched) {
                array_shift($matches); // Remove the full match
                
                $this->currentRoute = $routeData;
                
                // Set route parameters
                if (!empty($matches) && !empty($routeData['params'])) {
                    foreach ($routeData['params'] as $index => $paramName) {
                        if (isset($matches[$index])) {
                            $this->params[$paramName] = $matches[$index];
                        }
                    }
                }
                
                // Store debug info in the state if debug mode is enabled
                if (APP['debug'] ?? false) {
                    State::set('routeMatching', $debugInfo);
                    State::set('matchedRoute', [
                        'url' => $url,
                        'pattern' => $pattern,
                        'file' => $routeData['file'],
                        'type' => $routeData['type'],
                        'params' => $this->params
                    ]);
                }
                
                return true;
            }
        }
        
        // Store debug info about failed route matching if debug mode is enabled
        if (APP['debug'] ?? false) {
            State::set('routeMatching', $debugInfo);
            State::set('noMatchFound', true);
            State::set('requestedUrl', $url);
        }
        
        return false;
    }

    /**
     * Get the current URL path
     */
    public function getCurrentUrl() {
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        // Detect script directory automatically for XAMPP
        $scriptDir = dirname($_SERVER['SCRIPT_NAME']);
        $scriptDir = ($scriptDir === '/' || $scriptDir === '\\') ? '' : $scriptDir;
        
        // Use configured basePath if available, otherwise use detected scriptDir
        $basePath = $this->basePath ?: $scriptDir;
        
        // Remove base path from URL if it exists
        if ($basePath && strpos($path, $basePath) === 0) {
            $path = substr($path, strlen($basePath));
        }
        
        return $this->normalizeRoute($path);
    }
    
    /**
     * Get the current route type (PAGE or API)
     */
    public function getRouteType() {
        return $this->currentRoute['type'] ?? 'PAGE';
    }

    /**
     * Get a route parameter value
     */
    public function getParam($name) {
        return isset($this->params[$name]) ? $this->params[$name] : null;
    }

    /**
     * Get all route parameters
     */
    public function getParams() {
        return $this->params;
    }
    
    /**
     * Execute middleware stack
     */
    private function runMiddleware() {
        foreach ($this->middlewares as $middleware) {
            $result = $middleware($this);
            if ($result === false) {
                return false;
            }
        }
        return true;
    }

    /**
     * Dispatch the router to handle the current request
     */
    public function dispatch() {
        if (!$this->match()) {
            // No route matched
            $this->handleNotFound();
            return false;
        }
        
        // Run middleware stack
        if (!$this->runMiddleware()) {
            return false;
        }
        
        // Extract parameters to make them available in the included file
        extract($this->params);
        
        // Handle based on route type
        if ($this->getRouteType() === 'API') {
            $this->handleApiRoute();
        } else {
            // Include the page file
            include $this->currentRoute['file'];
        }
        
        return true;
    }
    
    /**
     * Handle API routes
     */
    private function handleApiRoute() {
        // Set proper content type for API responses
        header('Content-Type: application/json');
        
        // Extract parameters so they are available in the included file
        extract($this->params);
        
        // Check if file exists
        if (!file_exists($this->currentRoute['file'])) {
            header("HTTP/1.0 404 Not Found");
            echo json_encode([
                'error' => 'API File Not Found',
                'file' => $this->currentRoute['file'],
                'path' => $this->getCurrentUrl(),
                'status' => 404
            ]);
            return;
        }
        
        try {
            // Buffer the output to capture any errors or warnings
            ob_start();
            include $this->currentRoute['file'];
            $output = ob_get_clean();
            
            // If the output isn't already JSON, convert it
            if (is_string($output) && !empty($output)) {
                if (!$this->isJson($output)) {
                    echo json_encode(['data' => $output]);
                } else {
                    echo $output;
                }
            }
        } catch (Exception $e) {
            // Handle exceptions in API routes
            header("HTTP/1.0 500 Internal Server Error");
            echo json_encode([
                'error' => 'API Error',
                'message' => $e->getMessage(),
                'file' => $this->currentRoute['file'],
                'status' => 500
            ]);
        }
    }
    
    /**
     * Check if a string is valid JSON
     */
    private function isJson($string) {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }

    /**
     * Handle 404 Not Found
     */
    private function handleNotFound() {
        if ($this->isApiRequest()) {
            // API 404 response
            header("HTTP/1.0 404 Not Found");
            header('Content-Type: application/json');
            echo json_encode([
                'error' => 'Not Found',
                'message' => 'The requested endpoint does not exist.',
                'status' => 404
            ]);
        } else {
            // Web page 404 response
            header("HTTP/1.0 404 Not Found");
            
            // Get custom 404 page from config
            $notFoundPage = $this->config['notFoundPage'] ?? '/pages/404.php';
            
            // Check if the 404 page exists
            if (file_exists(APP['root'] . $notFoundPage)) {
                include APP['root'] . $notFoundPage;
            } else {
                // Fallback 404 page
                echo '<h1>404 Not Found</h1>';
                echo '<p>The page you requested was not found.</p>';
                
                // Show debug information if in debug mode
                if (APP['debug'] ?? false) {
                    echo '<hr>';
                    echo '<h3>Debug Information</h3>';
                    echo '<p>Requested URL: ' . $this->getCurrentUrl() . '</p>';
                    echo '<p>Available routes:</p>';
                    echo '<ul>';
                    foreach ($this->routes as $pattern => $route) {
                        $displayPattern = str_replace(['\/','(',')','\^','$'], ['/', '', '', '', ''], $pattern);
                        echo '<li>' . $displayPattern . ' => ' . $route['file'] . '</li>';
                    }
                    echo '</ul>';
                }
            }
        }
    }
    
    /**
     * Determine if the current request is an API request
     */
    private function isApiRequest() {
        $path = $this->getCurrentUrl();
        // Check if the path starts with /api or is a route with API type
        return strpos($path, '/api') === 0 || 
               (isset($this->currentRoute['type']) && $this->currentRoute['type'] === 'API');
    }
}