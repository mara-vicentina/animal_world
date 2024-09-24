import './bootstrap'

import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import 'vuetify/styles'
import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'
import { aliases, mdi } from 'vuetify/iconsets/mdi'

import 'vuetify/styles'
import '@mdi/font/css/materialdesignicons.css'
import '@fontsource/inter'


const vuetify = createVuetify({
  components,
  directives,
  theme: {
    defaultTheme: 'customTheme',
    themes: {
      customTheme: {
        typography: {
          fontFamily: 'Inter, sans-serif',
        },
        dark: false, // Mudar para `true` se quiser tema escuro por padrão
        colors: {
          error: '#FF5252',
          info: '#2196F3',
          success: '#4CAF50',
          warning: '#FFC107',
          danger: '#F44336',
          background: '#F5F5F5',
          primary: '#1C2751',
          secondary: '#344C92',
          accent: '#5572B2',
          500: '#003777',
          600: '#01538f',
          800: '#3d77b6',
          900: '#82A1D1',
        },
      },
    },
  },
  defaults: {
    global: {
      font: {
        family: 'Inter, sans-serif', // Define Inter como fonte global
      },
    },
  },
  typography: {
    // Remove a Roboto
    defaultFont: false,
  },
  icons: {
    defaultSet: 'mdi',
    aliases,
    sets: {
      mdi,
    },
  },
})

createInertiaApp({
  resolve: name => {
    const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
    return pages[`./Pages/${name}.vue`]
  },
  setup({ el, App, props, plugin }) {
    createApp({ render: () => h(App, props) })
      .use(plugin)
      .use(vuetify)
      .mount(el)
  },
})