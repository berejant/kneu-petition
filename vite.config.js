import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

/**
 * Bootswatch 3 підключає веб-шрифт через @import усередині міксина .web-font(),
 * що не компілюється в Less 4. Замінюємо міксин на звичайний CSS-імпорт.
 */
const bootswatchWebFont = {
    install(less, pluginManager) {
        pluginManager.addPreProcessor({
            process: (src, { fileInfo }) => fileInfo.filename.includes('bootswatch')
                ? src.replace(
                    /\.web-font\(@path\) \{[\s\S]*?\n\}\s*\.web-font\(@web-font-path\);/,
                    '@import (css) url("@{web-font-path}");',
                )
                : src,
        }, 1);
    },
};

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/less/app.less', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    css: {
        preprocessorOptions: {
            less: {
                // Bootstrap 3 / Bootswatch розраховані на Less 2.x, де ділення виконувалось без дужок
                math: 'always',
                plugins: [bootswatchWebFont],
            },
        },
    },
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
