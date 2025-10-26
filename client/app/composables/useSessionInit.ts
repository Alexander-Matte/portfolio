/**
 * Composable to initialize session on app mount
 * Use this in your app.vue or layout to automatically initialize the session
 */
export const useSessionInit = () => {
  const sessionStore = useSessionStore()

  const initSession = async () => {
    if (!sessionStore.session && !sessionStore.isLoading) {
      await sessionStore.initializeSession()
    }
  }

  // Auto-initialize on mount
  onMounted(() => {
    initSession()
  })

  return {
    initSession,
    session: sessionStore.session,
    isAuthenticated: sessionStore.isAuthenticated,
    isLoading: sessionStore.isLoading,
    error: sessionStore.error
  }
}

