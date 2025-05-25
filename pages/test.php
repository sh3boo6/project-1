<?php layout('start', ['title' => '404 - Page Not Found']) ?>
<div id="app" class="min-h-screen bg-gray-100 flex items-center justify-center">
    <div class="text-center">
        <main class="py-4">
            <h1 class="text-6xl font-bold text-gray-800 mb-4">404</h1>
            <p class="text-xl text-gray-600 mb-6">Oops! The page you're looking for doesn't exist.</p>
            <a href="<?= route('/') ?>" class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition duration-300">Back to Home</a>
        </main>
    </div>
</div>
<?php layout('scripts') ?>
<?php layout('end') ?>