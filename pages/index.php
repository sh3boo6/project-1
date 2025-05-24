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
                
            };
        },

        methods: {
            
        },

        mounted() {
            
        }
    }).mount('#app');
</script>
<?php layout('end') ?>