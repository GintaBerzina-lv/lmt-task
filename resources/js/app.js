import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createI18n } from 'vue-i18n';
import { createInertiaApp } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';
import BaseLayout from './Layouts/BaseLayout.vue';

import lvmessages from '../lang/lv.json';

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

const i18n = createI18n({
  locale: 'lv',
  fallbackLocale: 'lv',
  messages: {
    lv: lvmessages
  }
})

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => {
      const res = resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue'));
      res.then((page) => {
        page.default.layout = page.default.layout || BaseLayout;
      });
      return res;
    },
    setup({ el, app, props, plugin }) {
        const vueapp = createApp({ render: () => h(app, props) })
            .use(plugin)
            .use(i18n)
            .use(ZiggyVue, Ziggy);
        vueapp.config.globalProperties.$filters = {
          formatDateTime(value) {
            if (value) {
              return new Date(value).toLocaleString();
            }
            return '';
          }
        }
        vueapp.mount(el);
    },
});

InertiaProgress.init({ color: '#4B5563' });
