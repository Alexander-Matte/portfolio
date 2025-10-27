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

/**
 * API endpoint operation
 */
export interface ApiOperation {
  method: 'GET' | 'POST' | 'PUT' | 'PATCH' | 'DELETE' | 'UNKNOWN'
  path: string
  description: string | null
}

/**
 * API property metadata
 */
export interface ApiProperty {
  name: string
  type: string | null
  required: boolean
  readable: boolean
  writable: boolean
}

/**
 * API endpoint resource
 */
export interface ApiEndpoint {
  name: string
  operations: ApiOperation[]
  properties: ApiProperty[]
}

/**
 * API endpoints response
 */
export interface ApiEndpointsResponse {
  endpoints: ApiEndpoint[]
}

