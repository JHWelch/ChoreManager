import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { VitePWA } from 'vite-plugin-pwa';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
        VitePWA({
          includeAssets: [
            'favicon.ico',
            'robots.txt',
            'apple-touch-icon-180x180.png',
            'maskable-icon-512x512.png',
            'logo.svg',
          ],
          registerType: 'autoUpdate',
          manifest: {
            name: 'Chore Manager',
            theme_color: '#171717',
            icons: [
              {
                src: 'pwa-64x64.png',
                sizes: '64x64',
                type: 'image/png',
              },
              {
                src: 'pwa-192x192.png',
                sizes: '192x192',
                type: 'image/png',
              },
              {
                src: 'pwa-512x512.png',
                sizes: '512x512',
                type: 'image/png',
              },
              {
                src: 'maskable-icon-512x512.png',
                sizes: '512x512',
                type: 'image/png',
                purpose: 'maskable',
              },
            ],
          },
        }),
    ],
});
