import path from 'node:path';
import { fileURLToPath } from 'node:url';
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

const backendPath = path.dirname(fileURLToPath(import.meta.url));

export default defineConfig({
    resolve: {
        alias: {
            '@inertiajs/react': path.resolve(backendPath, 'node_modules/@inertiajs/react'),
            alpinejs: path.resolve(backendPath, 'node_modules/alpinejs'),
            'laravel-vite-plugin': path.resolve(backendPath, 'node_modules/laravel-vite-plugin'),
            'react-dom/client': path.resolve(backendPath, 'node_modules/react-dom/client'),
            react: path.resolve(backendPath, 'node_modules/react'),
        },
    },
    plugins: [
        laravel({
            input: [
                '../resources/css/app.css',
                '../resources/js/app.js',
                '../resources/js/app.jsx',
            ],
            refresh: true,
        }),
    ],
});
