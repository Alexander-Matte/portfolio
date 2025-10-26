/**
 * Session response from the API
 */
export interface SessionResponse {
  '@context': string
  '@id': string
  '@type': string
  username: string
  token: string
  expireAt: string
}

/**
 * Parsed session data for internal use
 */
export interface Session {
  context: string
  id: string
  type: string
  username: string
  token: string
  expireAt: Date
}

/**
 * Session state in the store
 */
export interface SessionState {
  session: Session | null
  isAuthenticated: boolean
  isLoading: boolean
  error: string | null
}

