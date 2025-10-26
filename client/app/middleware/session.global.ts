/**
 * Global middleware to initialize user session
 * Runs on every route navigation to ensure session is established
 */
export default defineNuxtRouteMiddleware(async (to, from) => {
  const sessionStore = useSessionStore()
  
  // Skip if session already exists and is valid
  if (sessionStore.isAuthenticated) {
    return
  }

  // Skip if already loading to prevent duplicate requests
  if (sessionStore.isLoading) {
    return
  }

  // Initialize session on first visit or when session is invalid/expired
  try {
    await sessionStore.initializeSession()
  } catch (error) {
    console.error('Failed to initialize session in middleware:', error)
    // Don't block navigation even if session initialization fails
    // The app can handle the error state from the store
  }
})

