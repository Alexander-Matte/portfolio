/**
 * Common API types and interfaces
 */

/**
 * Standard API error response
 */
export interface ApiError {
  '@context'?: string
  '@type'?: string
  'hydra:title'?: string
  'hydra:description'?: string
  message?: string
  status?: number
}

/**
 * JSON-LD context types
 */
export interface JsonLdContext {
  '@context': string
  '@id': string
  '@type': string
}

/**
 * API request options
 */
export interface ApiRequestOptions {
  method?: 'GET' | 'POST' | 'PUT' | 'PATCH' | 'DELETE'
  headers?: Record<string, string>
  body?: unknown
  params?: Record<string, string | number | boolean>
}

