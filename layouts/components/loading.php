<script>
    const Loading = {
        template: `
            <div class="loading-overlay" :class="{ 'show': visible }">
                <div class="spinner-container">
                    <div class="spinner-border text-light" role="status" style="width: 3rem; height: 3rem;">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-3 text-light" v-if="message">{{ message }}</p>
                </div>
            </div>
        `,
        props: {
            visible: {
                type: Boolean,
                default: false
            },
            message: {
                type: String,
                default: ''
            }
        }
    };

</script>

<style>
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100vh;
        background: linear-gradient(135deg, #0d6efd 0%, #0dcaf0 100%);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.3s ease, visibility 0.3s ease;
    }

    .loading-overlay.show {
        opacity: 0.95;
        visibility: visible;
    }

    .spinner-container {
        text-align: center;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }
</style>