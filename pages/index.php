<?php layout('start', ['title' => 'Home']) ?>
<div id="app">
    <app-loading :visible="loading"></app-loading>
    <div class="container" v-cloak>
        <main class="py-4">
            <div class="row justify-content-center">
                <div class="col-8 col-md-6">
                    <div class="card card-body text-center shadow-sm rounded-4">
                        <div class="lead text-end">Lorem ipsum, dolor sit amet consectetur adipisicing elit. Nostrum vel at iste atque consequatur quod veritatis voluptates earum, quaerat ducimus.</div>
                        <hr>
                        <div class="d-flex gap-2">
                            <a href="<?= route('/users') ?>" role="button" class="btn btn-primary w-100">Users</a>
                        </div>
                    </div>
                </div>
            </div>
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

        },

        mounted() {
            this.loading = false;
        }
    }).mount('#app');
</script>
<?php layout('end') ?>