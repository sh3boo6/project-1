<script>
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
 * Debug route generation - can be called from browser console
 * to troubleshoot URL generation issues
 */
function debugRoute(path, params = {}) {
    console.log('Debug route generation:');
    console.log('- Path: ', path);
    console.log('- Params: ', params);
    console.log('- Script Path: ', '<?= dirname($_SERVER["SCRIPT_NAME"]) ?>');
    console.log('- Base Path: ', ('<?= dirname($_SERVER["SCRIPT_NAME"]) ?>' === '/' || '<?= dirname($_SERVER["SCRIPT_NAME"]) ?>' === '\\') ? '' : '<?= dirname($_SERVER["SCRIPT_NAME"]) ?>');
    
    // Generate and log different URL forms
    const relativeUrl = route(path, params, false);
    const absoluteUrl = route(path, params, true);
    
    console.log('- Generated Relative URL: ', relativeUrl);
    console.log('- Generated Absolute URL: ', absoluteUrl);
    
    return {
        path,
        params,
        scriptPath: '<?= dirname($_SERVER["SCRIPT_NAME"]) ?>',
        basePath: ('<?= dirname($_SERVER["SCRIPT_NAME"]) ?>' === '/' || '<?= dirname($_SERVER["SCRIPT_NAME"]) ?>' === '\\') ? '' : '<?= dirname($_SERVER["SCRIPT_NAME"]) ?>',
        relativeUrl,
        absoluteUrl
    };
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
    const fetchOptions = {...defaultOptions, ...options};
    
    console.log(`Fetching API from: ${apiUrl}`);
    
    // Return fetch promise with automatic JSON parsing
    return fetch(apiUrl, fetchOptions)
        .then(response => {
            if (!response.ok) {
                throw new Error(`API Error (${response.status}): ${response.statusText}`);
            }
            return response.json();
        });
}
</script>
