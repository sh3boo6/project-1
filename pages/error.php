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
<style>
    .rotate--15 {
        transform: rotate(-15deg);
    }
</style>
<div id="app">
    <div class="container mx-auto">
        <main class="py-5">
            <div class="flex justify-center">
                <div class="md:w-2/3">
                    <div class="relative overflow-hidden my-5 p-5 bg-gray-100 rounded-lg shadow-2xl">
                        <!-- Animated element -->
                        <div class="absolute -top-4 -left-4 opacity-20 rotate--15">
                            <span class="text-8xl <?= $errorCode == 403 ? 'text-yellow-500' : 'text-red-500' ?>"><?= $errorCode ?></span>
                        </div>
                        
                        <div class="relative">
                            <!-- Main content -->
                            <div class="mb-5">
                                <h1 class="text-5xl font-bold <?= $errorCode == 403 ? 'text-yellow-500' : 'text-red-500' ?> mb-2"><?= $errorCode ?></h1>
                                <p class="text-4xl mb-4"><?= $errorTitle ?></p>
                                <div class="border-t border-b py-4 my-4">
                                    <p class="text-xl mb-0"><?= e($errorMessage) ?></p>
                                </div>
                            </div>
                            
                            <!-- Actions -->
                            <div class="flex flex-col md:flex-row gap-3 mt-4">
                                <a href="<?= route('/') ?>" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                                    <i class="bi bi-house-door mr-2"></i> Back to Home
                                </a>
                                <button onclick="window.history.back()" class="border border-gray-500 text-gray-600 px-4 py-2 rounded hover:bg-gray-100">
                                    <i class="bi bi-arrow-left mr-2"></i> Go Back
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