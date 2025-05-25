<?php layout('start', ['title' => 'Home']) ?>
<div id="app">
    <app-loading :visible="loading"></app-loading>
    <div class="container" v-cloak>
        <main class="py-4">
            
        </main>
    </div>
</div>
<?php layout('scripts') ?>
<?php layout('components/loading') ?>
<script>
    createAppWithGlobals({
        components: {
            'app-loading': Loading,
        },

        data() {
            return {
                loading: true,
            };
        },

        methods: {
            
        },

        mounted() {
            this.loading = false;
            this.$abort(403, 'You are not allowed to access this page.');
        }
    }).mount('#app');
</script>
<?php layout('end') ?>