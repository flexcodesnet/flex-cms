import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/panel.css',
                'resources/js/panel.js',
            ],
            refresh: true,
        }),
    ],
    build: {
        rollupOptions: {
            treeshake: false,
        },
    },
});
