interface RequestConfig {
  endpoint: string
  method: 'GET' | 'POST' | 'PUT' | 'PATCH' | 'DELETE'
  pathParams: Record<string, string>
  pathParameterNames: string[]
  formData: Record<string, any>
  token: string | null
}

export const useApiRequest = () => {
  const { buildApiUrl } = useApi()
  const responseData = ref<any>(null)
  const responseTime = ref<number | null>(null)
  const isLoading = ref(false)

  const executeRequest = async (config: RequestConfig) => {
    isLoading.value = true
    responseData.value = null
    responseTime.value = null

    try {
      const startTime = Date.now()
      
      let finalEndpoint = config.endpoint
      for (const paramName of config.pathParameterNames) {
        const paramValue = config.pathParams[paramName]
        if (!paramValue || paramValue.trim() === '') {
          responseData.value = { error: `Missing required path parameter: ${paramName}` }
          isLoading.value = false
          return
        }
        finalEndpoint = finalEndpoint.replace(`{${paramName}}`, encodeURIComponent(paramValue))
      }
      
      const headers: Record<string, string> = {
        'Accept': 'application/ld+json',
      }
      
      if (config.token) {
        headers['Authorization'] = `Bearer ${config.token}`
      }
      
      const options: any = {
        method: config.method,
        headers,
      }
      
      if (['POST', 'PUT', 'PATCH'].includes(config.method)) {
        headers['Content-Type'] = 'application/ld+json'
        
        const body: Record<string, any> = {}
        Object.entries(config.formData).forEach(([key, value]) => {
          if (value !== '' || typeof value === 'boolean' || typeof value === 'number') {
            body[key] = value
          }
        })
        
        options.body = body
      }
      
      const data = await $fetch(
        buildApiUrl(finalEndpoint),
        options
      )
      
      responseData.value = data
      responseTime.value = Date.now() - startTime
    } catch (error: any) {
      responseData.value = {
        error: error.message || 'Request failed',
        statusCode: error.statusCode,
        data: error.data
      }
    } finally {
      isLoading.value = false
    }
  }

  return {
    executeRequest,
    responseData,
    responseTime,
    isLoading,
  }
}

