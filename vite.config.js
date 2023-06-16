import { defineConfig } from 'vite';
import { createRequire } from 'node:module';
const require = createRequire( import.meta.url );
import laravel from 'laravel-vite-plugin';
import vue from "@vitejs/plugin-vue";
import ckeditor5 from '@ckeditor/vite-plugin-ckeditor5';
import AutoImport from 'unplugin-auto-import/vite'
import Components from 'unplugin-vue-components/vite'
import { ElementPlusResolver } from 'unplugin-vue-components/resolvers'
import VueI18nPlugin from '@intlify/unplugin-vue-i18n/vite'

export default defineConfig({

    plugins: [
        vue(),
        VueI18nPlugin({ /* options */ }),
        ckeditor5( { theme: require.resolve( '@ckeditor/ckeditor5-theme-lark' ) } ),
        laravel({
            input: ['resources/css/app.css', 'resources/js/make-article.js'],
            refresh: true,
        }),
        AutoImport({
            resolvers: [ElementPlusResolver()],
        }),
        Components({
            resolvers: [ElementPlusResolver()],
        }),
    ],
    // resolve: name => {
    //     const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
    //     return pages[`./Pages/${name}.vue`]
    // },
    optimizeDeps: {
        esbuildOptions: {
            legalComments: 'none'
        }
    },
});
