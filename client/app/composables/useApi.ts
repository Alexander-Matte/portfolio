/**
 * Composable for API configuration and utilities
 */
export const useApi = () => {
  const config = useRuntimeConfig()
  
  const apiBaseUrl = config.public.apiBaseUrl as string

  /**
   * Build a full API URL from a path
   * @param path - API endpoint path (e.g., '/api/sessions', '/api/users/123')
   * @returns Full API URL
   */
  const buildApiUrl = (path: string): string => {
    // Ensure path starts with /
    const normalizedPath = path.startsWith('/') ? path : `/${path}`
    return `${apiBaseUrl}${normalizedPath}`
  }

  return {
    apiBaseUrl,
    buildApiUrl
  }
}

