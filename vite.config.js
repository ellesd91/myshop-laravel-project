import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/scss/admin/admin.scss',
                'resources/js/app.js',
                'resources/js/admin/admin.js',
                'resources/js/dashboard.js',
            ],
            refresh: true,
        }),
    ],
});
