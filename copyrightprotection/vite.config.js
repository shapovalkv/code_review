import {defineConfig} from 'vite';
// eslint-disable-next-line import/no-unresolved
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js', 'resources/scss/app.scss'],
            refresh: true,
        })
    ],
    resolve: {
        alias: {
            '$': 'jQuery',
        },
    },
});
