// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  compatibilityDate: '2025-07-15',
  devtools: { enabled: true },

  // SSG mode - pre-render at build time
  ssr: false,

  modules: [
    '@nuxt/eslint',
    '@nuxt/fonts',
    '@nuxt/icon',
    '@nuxt/image',
    '@nuxt/ui',
    '@pinia/nuxt',
    'pinia-plugin-persistedstate/nuxt'
  ],
  
  css: ['~/assets/css/main.css'],

  // Development server configuration
  devServer: {
    host: '0.0.0.0', // Listen on all network interfaces
    port: 3000
  },

  vite: {
    server: {
      hmr: {
        protocol: 'ws',
        clientPort: 3000
      }
    }
  },

  // Runtime config - accessible on both client and server
  runtimeConfig: {
    // Public keys that are exposed to the client
    public: {
      apiBaseUrl: process.env.NUXT_PUBLIC_API_BASE_URL || 'http://localhost/api',
      mercureUrl: process.env.NUXT_PUBLIC_MERCURE_URL || 'http://localhost/.well-known/mercure'
    }
  }
})