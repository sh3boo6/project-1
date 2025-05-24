<?php 
$errorCode = isset($status) ? $status : (request('status', 500));
$errorMessage = isset($message) ? $message : (request('message', 'An error occurred'));
$errorTitle = '';

// Define error title based on status code
switch($errorCode) {
    case 400:
        $errorTitle = 'Bad Request';
        break;
    case 401:
        $errorTitle = 'Unauthorized';
        break;
    case 403:
        $errorTitle = 'Forbidden';
        break;
    case 404:
        $errorTitle = 'Not Found';
        break;
    case 419:
        $errorTitle = 'Page Expired';
        break;
    case 429:
        $errorTitle = 'Too Many Requests';
        break;
    case 500:
        $errorTitle = 'Server Error';
        break;
    case 503:
        $errorTitle = 'Service Unavailable';
        break;
    default:
        $errorTitle = 'Error';
}

layout('start', ['title' => $errorCode . ' | ' . $errorTitle]);
?>
<div id="app">
    <div class="container">
        <main class="py-5">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="error-container p-5 my-5 bg-light rounded-3 shadow-lg position-relative overflow-hidden">
                        <!-- Animated element -->
                        <div class="position-absolute" style="top: -15px; left: -15px; opacity: 0.15; transform: rotate(-15deg);">
                            <span class="display-1 text-<?= $errorCode == 403 ? 'warning' : 'danger' ?>"><?= $errorCode ?></span>
                        </div>
                        
                        <div class="position-relative">
                            <!-- Main content -->
                            <div class="mb-5">
                                <h1 class="display-4 fw-bold text-<?= $errorCode == 403 ? 'warning' : 'danger' ?> mb-2"><?= $errorCode ?></h1>
                                <p class="display-6 mb-4"><?= $errorTitle ?></p>
                                <div class="border-top border-bottom py-4 my-4">
                                    <p class="fs-5 mb-0"><?= e($errorMessage) ?></p>
                                </div>
                            </div>
                            
                            <!-- Actions -->
                            <div class="d-flex flex-column flex-md-row gap-3 mt-4">
                                <a href="<?= route('/') ?>" class="btn btn-primary px-4 py-2">
                                    <i class="bi bi-house-door me-2"></i> Back to Home
                                </a>
                                <button onclick="window.history.back()" class="btn btn-outline-secondary px-4 py-2">
                                    <i class="bi bi-arrow-left me-2"></i> Go Back
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
<?php layout('scripts') ?>
<?php layout('end') ?>