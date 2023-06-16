import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import { VueQueryPlugin } from "@tanstack/vue-query";
import { createI18n } from 'vue-i18n'
import ruMessages from "./lang/ru";
import enMessages from "./lang/en";

const messages = {
  ru: {
    ...ruMessages
  },
  en: {
    ...enMessages
  }
}

const i18n = createI18n({
  legacy: false, // you must set `false`, to use Composition API
  locale: 'en', // set locale
  fallbackLocale: 'ru ', // set fallback locale
  globalInjection: true,
  messages,
})

createInertiaApp({
  resolve: name => {
    const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
    return pages[`./Pages/${name}.vue`]
  },
  setup({ el, App, props, plugin }) {
    createApp({ render: () => h(App, props) })
      .use(plugin)
      .use(VueQueryPlugin)
      .use(i18n)
      .mount(el)
  },
})
