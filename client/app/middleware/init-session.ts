/**
 * Named middleware to initialize session
 * Can be applied to specific routes that require a session
 * 
 * Usage in a page:
 * definePageMeta({
 *   middleware: 'init-session'
 * })
 */
export default defineNuxtRouteMiddleware(async (to, from) => {
  const sessionStore = useSessionStore()

  // Only initialize if no valid session exists
  if (!sessionStore.isAuthenticated && !sessionStore.isLoading) {
    try {
      await sessionStore.initializeSession()
    } catch (error) {
      console.error('Session initialization failed:', error)
      // You could redirect to an error page here if needed
      // return navigateTo('/error')
    }
  }
})

