/**
 * This file demonstrates how to use the frontend abort function
 * for handling permissions and errors in your JavaScript code.
 */

// Example 1: Basic Usage
document.addEventListener('DOMContentLoaded', function() {
    // Find elements with permission check attributes
    document.querySelectorAll('[data-requires-permission]').forEach(element => {
        element.addEventListener('click', function(e) {
            const permission = this.getAttribute('data-requires-permission');
            const userPermissions = getUserPermissions(); // Your function to get user permissions
            
            // Check if user has the required permission
            if (!userPermissions.includes(permission)) {
                e.preventDefault();
                abort(403, `You don't have the "${permission}" permission required to perform this action.`);
                return false;
            }
        });
    });
    
    // Example auth check for protected sections
    const protectedSections = document.querySelectorAll('.requires-auth');
    if (protectedSections.length > 0) {
        const isLoggedIn = checkUserLoggedIn(); // Your function to check if user is logged in
        
        if (!isLoggedIn) {
            // Use abort without immediate redirect to get the URL
            const loginUrl = abort(401, 'Please log in to access this section.', false);
            
            // You could show a modal instead of redirecting
            showLoginModal(loginUrl);
        }
    }
});

// Example 2: Using abort in fetch requests
function fetchProtectedResource(resourceId) {
    fetch(`/api/protected-resource/${resourceId}`)
        .then(response => {
            if (!response.ok) {
                // Handle specific HTTP error codes
                switch (response.status) {
                    case 401:
                        abort(401, 'You must be logged in to access this resource.');
                        break;
                    case 403:
                        abort(403, 'You do not have permission to access this resource.');
                        break;
                    case 404:
                        abort(404, `Resource #${resourceId} could not be found.`);
                        break;
                    default:
                        abort(response.status);
                        break;
                }
                return;
            }
            return response.json();
        })
        .then(data => {
            if (data) {
                console.log('Resource data:', data);
            }
        })
        .catch(error => console.error('Error fetching resource:', error));
}

// Example 3: Permission checking in a Vue component
// This would go in your Vue component code
/*
methods: {
    checkAdminAccess() {
        const isAdmin = this.$store.state.user.roles.includes('admin');
        if (!isAdmin) {
            this.$abort(403, 'Admin access required');
            return false;
        }
        return true;
    },
    
    performAdminAction() {
        if (this.checkAdminAccess()) {
            // Proceed with admin action
            this.loadAdminDashboard();
        }
    }
}
*/

// Helper functions (placeholders - implement your own versions)
function checkUserLoggedIn() {
    // Check if user is logged in
    // Example implementation:
    return !!localStorage.getItem('user_token');
}

function getUserPermissions() {
    // Get user permissions from localStorage, cookie, or your auth system
    // Example implementation:
    const permissionsStr = localStorage.getItem('user_permissions');
    return permissionsStr ? permissionsStr.split(',') : [];
}

function showLoginModal(redirectUrl) {
    // Show a login modal instead of redirecting
    console.log('Showing login modal, will redirect to:', redirectUrl);
    
    // Implementation depends on your UI framework
    // Example: Bootstrap modal
    /*
    const modal = new bootstrap.Modal(document.getElementById('loginModal'));
    document.getElementById('login-redirect').value = redirectUrl;
    modal.show();
    */
}
