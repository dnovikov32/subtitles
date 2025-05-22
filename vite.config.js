import { defineConfig } from 'vite';
import { fileURLToPath, URL } from 'node:url'
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    resolve: {
        alias: {
            '@': fileURLToPath(new URL('./src', import.meta.url)),
            vue: '@vue/compat',
        }
    },
    build: {
        outDir: './public/dist',
        rollupOptions: {
            output: {
                entryFileNames: `assets/[name].js`,
                chunkFileNames: `assets/[name].js`,
                assetFileNames: `assets/[name].[ext]`
            }
        }
    },
    plugins: [
        laravel({
            input: [
                'resources/scss/app.scss',
                'resources/js/app.js'
            ],
            refresh: true,
        }),
        vue({
            template: {
                compilerOptions: {
                    compatConfig: {
                        MODE: 2
                    }
                }
            }
        }),
        tailwindcss(),
    ],
});
