import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/print.css',
                'resources/js/app.js',
                'resources/js/pages/report/index.js',
                'resources/js/pages/departments/index.js',
                'resources/js/pages/organizations/index.js',
                'resources/js/pages/offices/index.js',
                'resources/js/pages/users/index.js',
                'resources/js/evaluations/behavior/create.js',
                'resources/js/evaluations/behavior/preview.js',
                'resources/js/evaluations/behavior/view.js',
                'resources/js/evaluations/work_performance/table.js',
                'resources/js/evaluations/work_performance/progressbar.js',
                'resources/js/evaluations/work_performance/navigation.js',
                'resources/js/evaluations/work_performance/create.js',
                'resources/js/evaluations/work_performance/edit.js',
                'resources/js/evaluations/attendance/progressbar.js',
                'resources/js/evaluations/attendance/form.js',
                'resources/js/evaluations/attendance/navigation.js',
                'resources/js/evaluations/behavior/index.js',
                'resources/js/evaluations/behavior/review.js',
                'resources/js/evaluations/attendance/edit.js',
                'resources/js/pages/evaluation-results/outcome.js',

            ],
            refresh: true,
        }),
        tailwindcss(),
    ],

    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});