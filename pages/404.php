<?php layout('start', ['title' => '404 - Page Not Found']) ?>
<style>
    .error-container {
        background: linear-gradient(145deg, #f8f9fa, #e9ecef);
        border-left: 5px solid #dc3545;
    }
    .floating-404 {
        animation: float 6s ease-in-out infinite;
        z-index: 0;
    }
    .error-container .content {
        z-index: 1;
        position: relative;
    }
    @keyframes float {
        0% { transform: translateY(0px) rotate(-15deg); }
        50% { transform: translateY(-20px) rotate(-10deg); }
        100% { transform: translateY(0px) rotate(-15deg); }
    }
    @keyframes floatRight {
        0% { transform: translateY(0px) rotate(10deg); }
        50% { transform: translateY(-15px) rotate(15deg); }
        100% { transform: translateY(0px) rotate(10deg); }
    }
    .bounce-in {
        animation: bounceIn 1s ease;
    }
    @keyframes bounceIn {
        0% { transform: scale(0.1); opacity: 0; }
        60% { transform: scale(1.1); opacity: 1; }
        100% { transform: scale(1); }
    }
</style>
<div id="app">
    <div class="container">
        <main class="py-5">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <div class="error-container p-5 my-5 rounded-3 shadow-lg position-relative overflow-hidden">
                        <!-- Animated elements -->
                        <div class="position-absolute floating-404" style="top: -20px; left: -20px;">
                            <span class="display-1 text-danger opacity-25">404</span>
                        </div>
                        <div class="position-absolute floating-404" style="bottom: -15px; right: -15px; animation: floatRight 7s ease-in-out infinite;">
                            <span class="display-1 text-danger opacity-25">404</span>
                        </div>
                        
                        <div class="content position-relative">
                            <!-- Main content -->
                            <div class="mb-5 bounce-in">
                                <h1 class="display-1 fw-bold text-danger mb-0">404</h1>
                                <p class="display-6 mb-4">Page Not Found</p>
                                <div class="border-top border-bottom py-4 my-4">
                                    <p class="fs-5 mb-2">Oops! The page you're looking for seems to have gone on an adventure without us.</p>
                                    <p class="text-muted">Don't worry, even the best explorers get lost sometimes!</p>
                                </div>
                                
                                <!-- Lost item illustration -->
                                <div class="my-4 d-flex justify-content-center">
                                    <div class="position-relative d-inline-block">
                                        <svg width="150" height="100" viewBox="0 0 150 100" class="mb-3">
                                            <path d="M10,80 Q75,30 140,80" stroke="#dc3545" stroke-width="2" fill="none" />
                                            <circle cx="75" cy="30" r="15" fill="#ffc107" />
                                            <circle cx="75" cy="30" r="20" stroke="#ffc107" stroke-width="2" fill="none">
                                                <animate attributeName="r" from="20" to="25" dur="2s" repeatCount="indefinite" />
                                                <animate attributeName="opacity" from="1" to="0" dur="2s" repeatCount="indefinite" />
                                            </circle>
                                        </svg>
                                        <div class="position-absolute top-100 start-50 translate-middle">
                                            <i class="bi bi-emoji-dizzy-fill text-warning display-5"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Actions -->
                            <div class="d-flex flex-column flex-md-row justify-content-center gap-3 mt-4">
                                <a href="<?= route('/') ?>" class="btn btn-primary btn-lg px-5 py-3 shadow-sm">
                                    <i class="bi bi-house-door-fill me-2"></i> Back to Home
                                </a>
                                <button onclick="window.history.back()" class="btn btn-outline-danger btn-lg px-5 py-3">
                                    <i class="bi bi-arrow-left me-2"></i> Go Back
                                </button>
                            </div>
                            <div class="mt-4 d-flex justify-content-center">
                                <a href="javascript:void(0);" onclick="reportBrokenLink()" class="text-decoration-none text-muted">
                                    <i class="bi bi-exclamation-triangle me-1"></i> Report broken link
                                </a>
                            </div>
                            
                            <script>
                                function reportBrokenLink() {
                                    alert('Thank you! This broken link has been reported to our team.');
                                }
                            </script>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
<?php layout('scripts') ?>
<?php layout('end') ?>