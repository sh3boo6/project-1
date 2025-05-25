<?php layout('start', ['title' => 'Home']) ?>
<div id="app">
    <app-loading :visible="loading"></app-loading>
    <div class="container" v-cloak>
        <main class="py-4">
            <pre dir="ltr" class="text-white bg-secondary rounded-3 p-3">{{ data }}</pre>
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
                api: this.$route('api/data'),
                data: null,
            };
        },

        methods: {
            async fetchData() {
                try {
                    const data = await fetch(this.api);
                    const res = await data.json();
                    this.data = res;
                    console.log(res);
                } catch (error) {
                    console.error('Error fetching data:', error);
                }
            }
        },

        mounted() {
            this.fetchData();
            
            setTimeout(() => {
                this.loading = false;
            }, 50); // Simulate loading for 1 second
        }
    }).mount('#app');
</script>
<?php layout('end') ?>