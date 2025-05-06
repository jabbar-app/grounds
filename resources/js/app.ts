import '../css/app.css';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import type { DefineComponent } from 'vue';
import { createApp, h } from 'vue';
import { ZiggyVue } from 'ziggy-js';
import { initializeTheme } from './composables/useAppearance';
import { createHead, useHead } from '@vueuse/head';

const appName = import.meta.env.VITE_APP_NAME || 'Grounds';

const head = createHead(); // ✅ create head instance

createInertiaApp({
  title: (title) => `${title} - ${appName}`,
  resolve: (name) => resolvePageComponent(`./pages/${name}.vue`, import.meta.glob<DefineComponent>('./pages/**/*.vue')),
  setup({ el, App, props, plugin }) {
    const vueApp = createApp({ render: () => h(App, props) });

    // ✅ Use plugins
    vueApp
      .use(plugin)
      .use(ZiggyVue)
      .use(head) // ⬅️ Register head globally
      .mount(el);

    // ✅ Set global meta & favicon
    useHead({
      title: 'Grounds - Platform Pembelajaran Interaktif & Aman',
      meta: [
        {
          name: 'description',
          content: 'Grounds adalah platform quiz dan pembelajaran digital yang seru, adaptif, dan terpercaya untuk semua jenjang pendidikan di Indonesia.',
        },
        {
          name: 'keywords',
          content: 'Grounds, quiz online, pembelajaran digital, pendidikan Indonesia, platform edukasi',
        },
        {
          property: 'og:title',
          content: 'Grounds - Platform Belajar Interaktif',
        },
        {
          property: 'og:type',
          content: 'website',
        },
        {
          name: 'viewport',
          content: 'width=device-width, initial-scale=1.0',
        },
      ],
      link: [
        {
          rel: 'icon',
          href: '/favicon.ico',
        },
      ],
    });
  },
  progress: {
    color: '#4B5563',
  },
});

initializeTheme();
