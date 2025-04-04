import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/splide.js',
                'resources/js/glightbox.js',
                'resources/css/filament/auth/theme.css',
            ],
            refresh: true,
        }),
    ],
});
