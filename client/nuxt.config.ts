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
    '@pinia/nuxt'
  ],
  
  css: ['~/assets/css/main.css'],

  // Runtime config - accessible on both client and server
  runtimeConfig: {
    // Public keys that are exposed to the client
    public: {
      apiBaseUrl: '' // Will be populated from NUXT_PUBLIC_API_BASE_URL env var
    }
  }
})