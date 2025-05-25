<?php layout('start', ['title' => '404 - Page Not Found']) ?>
<style>
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
    @keyframes bounceIn {
        0% { transform: scale(0.1); opacity: 0; }
        60% { transform: scale(1.1); opacity: 1; }
        100% { transform: scale(1); }
    }
</style>
<div id="app">
    <div class="container mx-auto">
        <main class="py-5">
            <div class="flex justify-center">
                <div class="lg:w-2/3 text-center">
                    <div class="relative overflow-hidden my-5 p-5 rounded-lg shadow-2xl bg-gradient-to-br from-gray-100 to-gray-200 border-l-4 border-red-500">
                        <!-- Animated elements -->
                        <div class="absolute -top-5 -left-5 animate-[float_6s_ease-in-out_infinite] z-0">
                            <span class="text-8xl text-red-500 opacity-25">404</span>
                        </div>
                        <div class="absolute -bottom-4 -right-4 animate-[floatRight_7s_ease-in-out_infinite] z-0">
                            <span class="text-8xl text-red-500 opacity-25">404</span>
                        </div>
                        
                        <div class="relative z-10">
                            <!-- Main content -->
                            <div class="mb-5 animate-[bounceIn_1s_ease]">
                                <h1 class="text-8xl font-bold text-red-500 mb-0">404</h1>
                                <p class="text-4xl mb-4">Page Not Found</p>
                                <div class="border-t border-b py-4 my-4">
                                    <p class="text-xl mb-2">Oops! The page you're looking for seems to have gone on an adventure without us.</p>
                                    <p class="text-gray-600">Don't worry, even the best explorers get lost sometimes!</p>
                                </div>
                                
                            </div>
                            
                            <!-- Actions -->
                            <div class="flex flex-col md:flex-row justify-center gap-3 mt-4">
                                <a href="<?= route('/') ?>" class="btn bg-blue-600 text-white px-5 py-3 rounded shadow hover:bg-blue-700">
                                    <i class="bi bi-house-door-fill mr-2"></i> Back to Home
                                </a>
                                <button onclick="window.history.back()" class="btn border border-red-500 text-red-500 px-5 py-3 rounded hover:bg-red-50">
                                    <i class="bi bi-arrow-left mr-2"></i> Go Back
                                </button>
                            </div>
                            <div class="mt-4 flex justify-center">
                                <a href="javascript:void(0);" onclick="reportBrokenLink()" class="text-gray-600 hover:text-gray-800 no-underline">
                                    <i class="bi bi-exclamation-triangle mr-1"></i> Report broken link
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