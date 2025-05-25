<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <title><?= APP['name'] ?> <?= isset($title) ? ' | ' . $title : null ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.rtl.min.css" integrity="sha384-MdqCcafa5BLgxBDJ3d/4D292geNL64JyRtSGjEszRUQX9rhL1QkcnId+OT7Yw+D+" crossorigin="anonymous">
    
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="<?= asset('js/tailwindcss.js') ?>"></script>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <!-- Vue CDN -->
    <script src="https://unpkg.com/vue@latest"></script>
    
    <!-- Material Icons -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />

    <script>
        // Vue initialization
        const {
            createApp
        } = Vue;

        // Vue Global Methods - available in all component templates
        const vueGlobalMethods = {
            // Route helper - same as global route function but accessible in Vue templates
            $route(path, params = {}, absolute = false) {
                return route(path, params, absolute);
            },

            /**
             * Frontend abort function - similar to the PHP abort() function
             * Redirects to the error page with status code and message
             * 
             * @param {number} status - HTTP status code (e.g., 403 for Forbidden)
             * @param {string} message - Optional error message
             * @param {boolean} redirect - Whether to redirect or just return the URL (default: true)
             * @return {string|void} - The error page URL if redirect is false, otherwise redirects
             */
            $abort(status = 403, message = null, redirect = true) {
                // Default messages based on status code
                const defaultMessages = {
                    400: 'Bad request. The server cannot process the request due to client error.',
                    401: 'Unauthorized. Authentication is required to access this resource.',
                    403: 'Forbidden. You do not have permission to access this resource.',
                    404: 'The requested resource could not be found.',
                    419: 'Your session has expired. Please refresh and try again.',
                    429: 'Too many requests. Please try again later.',
                    500: 'Internal server error. Something went wrong on our end.',
                    503: 'Service unavailable. The server is temporarily unable to handle the request.'
                };
                
                // Use provided message or default message for the status code
                const errorMessage = message || defaultMessages[status] || 'An error occurred while processing your request.';
                
                // Build the URL to the error page with query parameters
                const errorUrl = route('error') + `?status=${status}&message=${encodeURIComponent(errorMessage)}`;
                
                if (redirect) {
                    // Perform the redirect
                    window.location.href = errorUrl;
                } else {
                    // Just return the URL without redirecting
                    return errorUrl;
                }
            },

            // API helper - same as global fetchApi but accessible in Vue templates
            $api(path, options = {}, params = {}) {
                return fetchApi(path, options, params);
            },

            // Format currency
            $currency(amount, currency = 'SAR') {
                return new Intl.NumberFormat('ar-SA', {
                    style: 'currency',
                    currency: currency
                }).format(amount);
            },

            // Format date
            $date(date, options = {}) {
                const defaultOptions = {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                };
                return new Date(date).toLocaleDateString('ar-SA', {
                    ...defaultOptions,
                    ...options
                });
            },

            // Truncate text
            $truncate(text, length = 100, suffix = '...') {
                if (text.length <= length) return text;
                return text.substring(0, length) + suffix;
            },

            // Debug helper
            $debug(data, label = 'Debug') {
                if (siteConfig.debug) {
                    console.log(`[${label}]`, data);
                }
                return data;
            },

            // Get app config
            $config(key = null) {
                return key ? siteConfig[key] : siteConfig;
            },

            // Get app state
            $state(key = null) {
                return key ? appState[key] : appState;
            },

            // Asset URL helpers
            $assets(path = '', absolute = false, version = null) {
                // Get script directory for subdirectory installations
                const scriptPath = '<?= dirname($_SERVER["SCRIPT_NAME"]) ?>';
                const basePath = (scriptPath === '/' || scriptPath === '\\') ? '' : scriptPath;

                // Clean up the path
                let assetPath = 'assets/' + path.replace(/^\/+/, '');

                // Add version parameter for cache busting if provided
                if (version !== null) {
                    const separator = assetPath.includes('?') ? '&' : '?';
                    assetPath += separator + 'v=' + version;
                }

                // Return either absolute or relative URL
                if (absolute) {
                    const protocol = window.location.protocol;
                    const host = window.location.host;
                    return `${protocol}//${host}${basePath}/${assetPath}`;
                } else {
                    return `${basePath}/${assetPath}`;
                }
            },

            // CSS file helper
            $css(filename, absolute = false, version = null) {
                if (!filename.endsWith('.css')) {
                    filename += '.css';
                }
                return this.$assets('css/' + filename, absolute, version);
            },

            // JavaScript file helper
            $js(filename, absolute = false, version = null) {
                if (!filename.endsWith('.js')) {
                    filename += '.js';
                }
                return this.$assets('js/' + filename, absolute, version);
            },

            // Image file helper
            $img(filename, absolute = false, version = null) {
                return this.$assets('img/' + filename, absolute, version);
            },

            // Font file helper
            $font(filename, absolute = false, version = null) {
                return this.$assets('font/' + filename, absolute, version);
            }
        };

        // Create a wrapper function to add global properties to Vue apps
        window.createAppWithGlobals = function(options = {}) {
            const app = createApp(options);

            // Add global properties (accessible in templates as this.$route, this.$api, etc.)
            Object.entries(vueGlobalMethods).forEach(([name, method]) => {
                app.config.globalProperties[name] = method;
            });

            return app;
        };

        // Global site configuration
        const siteConfig = {
            baseUrl: '<?= APP['baseUrl'] ?>',
            appName: '<?= APP['name'] ?>',
            debug: <?= APP['debug'] ? 'true' : 'false' ?>,
            version: '<?= APP['version'] ?>'
        };

        // URL related globals
        const currentUrl = '<?= get_current_url() ?>';
        const fullUrl = '<?= get_full_url() ?>';

        // Global application state
        const appState = <?= json_encode(State::all()) ?>;

        /**
         * Generate a URL to a route by path name
         * JavaScript version of the PHP route() function
         * 
         * @param {string} path - The route path (without leading slash)
         * @param {object} params - Optional parameters for dynamic routes
         * @param {boolean} absolute - Whether to return an absolute URL (with domain)
         * @return {string} The URL to the route
         */
        function route(path, params = {}, absolute = false) {
            // Get base path from script directory
            const scriptPath = '<?= dirname($_SERVER["SCRIPT_NAME"]) ?>';
            const basePath = (scriptPath === '/' || scriptPath === '\\') ? '' : scriptPath;

            // Clean the path
            let url = path.replace(/^\/+/, '');

            // Handle empty path (home page)
            if (!url) {
                return basePath || '/';
            }

            // Replace parameter placeholders for dynamic routes
            if (params && typeof params === 'object') {
                Object.entries(params).forEach(([key, value]) => {
                    // Replace both [param] and :param formats
                    url = url.replace(`[${key}]`, value);
                    url = url.replace(new RegExp(`:${key}(?=[\/\\?]|$)`, 'g'), value);
                });
            }

            // Return either absolute or relative URL with correct base path
            if (absolute) {
                const protocol = window.location.protocol;
                const host = window.location.host;
                return `${protocol}//${host}${basePath}/${url}`;
            } else {
                // Always include the basePath, whether it's a page or API URL
                return `${basePath}/${url}`;
            }
        }

        /**
         * Utility function to fetch data from API endpoints
         * Uses the route() function to ensure paths are correct
         * 
         * @param {string} path - API path (e.g. 'api/users/all')
         * @param {object} options - Fetch API options
         * @param {object} params - Route parameters for dynamic routes
         * @return {Promise} - Fetch promise that resolves to JSON response
         */
        function fetchApi(path, options = {}, params = {}) {
            // Generate the correct URL with proper base path
            const apiUrl = route(path, params);

            // Default options with JSON handling
            const defaultOptions = {
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                }
            };

            // Merge with user options
            const fetchOptions = {
                ...defaultOptions,
                ...options
            };

            console.log(`Fetching API from: ${apiUrl}`);

            // Return fetch promise with automatic JSON parsing
            return fetch(apiUrl, fetchOptions)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .catch(error => {
                    console.error('API fetch error:', error);
                    throw error;
                });
        }

        /**
         * Global abort function for client-side error handling
         * Redirects to the error page with status code and message
         * 
         * @param {number} status - HTTP status code (e.g., 403 for Forbidden)
         * @param {string} message - Optional error message
         * @param {boolean} redirect - Whether to redirect or just return the URL
         * @return {string|void} - The error URL or void if redirecting
         */
        function abort(status = 403, message = null, redirect = true) {
            // Use the Vue method implementation for consistency
            // This makes the function available globally outside Vue components
            return vueGlobalMethods.$abort(status, message, redirect);
        }
    </script>

    <style>
        [v-cloak] {
            display: none !important;
        }
    </style>

    <link rel="stylesheet" href="<?= asset('css/output.css') ?>">
    
</head>
