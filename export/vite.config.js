import {defineConfig} from 'vite';
import tailwindcss from '@tailwindcss/vite'
import laravel from 'laravel-vite-plugin';
import statamic from '@statamic/cms/vite-plugin';
import { dokThemes } from './vite-dok-themes.js';

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
        dokThemes(),
    ],
    server: {
        watch: {
            ignored: [
                '**/storage/framework/views/**',
            ],
        },
    },
});
