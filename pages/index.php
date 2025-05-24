<?php layout('start', ['title' => 'Home']) ?>
<div id="app">
    <app-loading :visible="loading"></app-loading>
    <div class="container" v-cloak>
        <main class="py-4">
            <div class="lead">Lorem ipsum dolor sit amet consectetur adipisicing elit. Laboriosam, rerum.</div>
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
            setTimeout(() => {
                this.loading = false;
            }, 1000); // Simulate loading for 1 second
        }
    }).mount('#app');
</script>
<?php layout('end') ?>