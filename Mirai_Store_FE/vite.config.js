import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        proxy: {
            '/api/backend': {
                target: 'http://localhost:5185',
                changeOrigin: true,
                rewrite: (path) => path.replace(/^\/api\/backend/, '/api'),
            },
        },
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
