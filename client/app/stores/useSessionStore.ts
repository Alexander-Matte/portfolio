import { defineStore } from 'pinia'
import type { Session, SessionResponse } from '~/types/session'

export const useSessionStore = defineStore('session', () => {
  // State
  const session = ref<Session | null>(null)
  const isLoading = ref(false)
  const error = ref<string | null>(null)

  // Getters
  const isAuthenticated = computed(() => {
    if (!session.value) return false
    
    // Check if token is expired
    const now = new Date()
    return session.value.expireAt > now
  })

  const username = computed(() => session.value?.username ?? null)
  const token = computed(() => session.value?.token ?? null)

  // Actions
  const setSession = (sessionResponse: SessionResponse) => {
    session.value = {
      context: sessionResponse['@context'],
      id: sessionResponse['@id'],
      type: sessionResponse['@type'],
      username: sessionResponse.username,
      token: sessionResponse.token,
      expireAt: new Date(sessionResponse.expireAt)
    }
    error.value = null
  }

  const clearSession = () => {
    session.value = null
    error.value = null
  }

  const setError = (errorMessage: string) => {
    error.value = errorMessage
    isLoading.value = false
  }

  const setLoading = (loading: boolean) => {
    isLoading.value = loading
  }

  const initializeSession = async () => {
    isLoading.value = true
    error.value = null

    try {
      const { buildApiUrl } = useApi()
      
      const response = await $fetch<SessionResponse>(buildApiUrl('/api/sessions'), {
        method: 'POST',
        headers: {
          'accept': 'application/ld+json',
          'Content-Type': 'application/ld+json'
        },
        body: {}
      })

      setSession(response)
    } catch (err) {
      const errorMessage = err instanceof Error ? err.message : 'Failed to initialize session'
      setError(errorMessage)
      console.error('Session initialization error:', err)
    } finally {
      isLoading.value = false
    }
  }

  return {
    // State
    session: readonly(session),
    isLoading: readonly(isLoading),
    error: readonly(error),
    
    // Getters
    isAuthenticated,
    username,
    token,
    
    // Actions
    setSession,
    clearSession,
    setError,
    setLoading,
    initializeSession
  }
})
