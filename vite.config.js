import {defineConfig} from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/scss/app.scss',
                'resources/scss/dashboard.scss',
                'resources/js/app.js',
                'resources/js/beneficiaries.js',
                'resources/js/categories.js',
                'resources/js/dashboard.js',
                'resources/js/helpers/filters.js',
                'resources/js/helpers/forms.js',
                'resources/js/recurrences.js',
                'resources/js/labels.js',
                'resources/js/transactions.js'
            ],
            refresh: true,
        }),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
    css: {
        preprocessorOptions: {
            scss: {
                silenceDeprecations: ['mixed-decls', 'color-functions', 'global-builtin', 'import']
            },
        }
    },
});
