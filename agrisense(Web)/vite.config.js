import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";

export default defineConfig({
    // server: {
    //     hmr: {
    //         host: 'law.alephaz.com',
    //     },
    //     // watch: {
    //     //     usePolling: true
    //     // },
    //     port: 80
    // },
    plugins: [
        laravel({
            input: [
                "resources/css/app.css",
                "resources/js/app.js",
                "resources/css/dashboard/dashboard.css",
                "resources/js/dashboard/dashboard.css",
                "resources/js/analytics/analytics.css",
            ],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    resolve: {
        alias: {
            vue: "vue/dist/vue.esm-bundler.js",
        },
    },
});
