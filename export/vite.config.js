import {defineConfig} from 'vite';
import tailwindcss from '@tailwindcss/vite'
import laravel from 'laravel-vite-plugin';
import statamic from '@statamic/cms/vite-plugin';


export default defineConfig({
    plugins: [
        statamic(),
        laravel({
            input: [
                'resources/css/site.css',
                'resources/js/site/index.js'
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: [
                '**/storage/framework/views/**',
            ],
        },
    },
});
