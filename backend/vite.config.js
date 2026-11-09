import path from 'node:path';
import { fileURLToPath } from 'node:url';
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

const backendPath = path.dirname(fileURLToPath(import.meta.url));

export default defineConfig({
    root: path.resolve(backendPath, '..'),
    envDir: backendPath,
    cacheDir: path.resolve(backendPath, 'node_modules/.vite'),
    resolve: {
        alias: {
            '@inertiajs/react': path.resolve(backendPath, 'node_modules/@inertiajs/react'),
            alpinejs: path.resolve(backendPath, 'node_modules/alpinejs'),
            'laravel-vite-plugin': path.resolve(backendPath, 'node_modules/laravel-vite-plugin'),
            'react-dom/client': path.resolve(backendPath, 'node_modules/react-dom/client'),
            react: path.resolve(backendPath, 'node_modules/react'),
        },
    },
    css: {
        postcss: path.resolve(backendPath, 'postcss.config.js'),
    },
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/app.jsx',
            ],
            publicDirectory: 'public',
            hotFile: 'public/hot',
            refresh: true,
        }),
    ],
    build: {
        outDir: path.resolve(backendPath, 'public/build'),
        emptyOutDir: true,
    },
});
