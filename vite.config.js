import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/inputsYBotones.css',
                'resources/css/productos.css',
                'resources/css/pedidoDetalle.css',
                'resources/css/admin/dashboard.css',
                'resources/js/app.js',
                'resources/js/bootstrap.js',
                'resources/js/fecha.js',
                'resources/js/pedidoDetalle.js',
            ],
            refresh: true,
        }),
    ],
});