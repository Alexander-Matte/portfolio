import type { ApiEndpointsResponse, ApiOperation } from '~/types/api'

export const useApiExplorer = (endpointsData: Ref<ApiEndpointsResponse | null | undefined>) => {
  const allOperations = computed(() => {
    if (!endpointsData.value?.endpoints) return []
    
    const operations: ApiOperation[] = []
    endpointsData.value.endpoints.forEach(endpoint => {
      endpoint.operations.forEach(operation => {
        operations.push(operation)
      })
    })
    return operations
  })

  const endpointOptions = computed(() => {
    const uniquePaths = new Set<string>()
    const options: { label: string; value: string }[] = []
    
    allOperations.value.forEach(operation => {
      if (!uniquePaths.has(operation.path)) {
        uniquePaths.add(operation.path)
        options.push({
          label: operation.path,
          value: operation.path
        })
      }
    })
    
    return options
  })

  const selectedEndpoint = ref<string>('/api/endpoints')
  const selectedMethod = ref<'GET' | 'POST' | 'PUT' | 'PATCH' | 'DELETE'>('GET')
  const pathParams = ref<Record<string, string>>({})

  const availableMethods = computed((): Array<'GET' | 'POST' | 'PUT' | 'PATCH' | 'DELETE'> => {
    if (!selectedEndpoint.value) return []
    
    return allOperations.value
      .filter(op => op.path === selectedEndpoint.value)
      .map(op => op.method)
      .filter((method): method is 'GET' | 'POST' | 'PUT' | 'PATCH' | 'DELETE' => method !== 'UNKNOWN')
  })

  const pathParameterNames = computed(() => {
    if (!selectedEndpoint.value) return []
    const matches = selectedEndpoint.value.match(/\{([^}]+)\}/g)
    if (!matches) return []
    return matches.map(match => match.slice(1, -1))
  })

  const currentEndpointResource = computed(() => {
    if (!selectedEndpoint.value || !endpointsData.value?.endpoints) return null
    
    return endpointsData.value.endpoints.find(endpoint => 
      endpoint.operations.some(op => op.path === selectedEndpoint.value)
    )
  })

  const writableProperties = computed(() => {
    const resource = currentEndpointResource.value
    if (!resource || !resource.properties) return []
    
    return resource.properties.filter(prop => 
      prop.writable && 
      !['id', 'createdAt', 'updatedAt', 'userId'].includes(prop.name) &&
      prop.type !== 'object'
    )
  })

  const formData = ref<Record<string, any>>({})

  watch([selectedEndpoint, selectedMethod], () => {
    formData.value = {}
    
    writableProperties.value.forEach(prop => {
      if (prop.type === 'bool') {
        formData.value[prop.name] = false
      } else if (prop.type === 'int') {
        formData.value[prop.name] = 0
      } else {
        formData.value[prop.name] = ''
      }
    })
    
    if (availableMethods.value.length > 0) {
      const firstMethod = availableMethods.value[0]
      if (firstMethod && !availableMethods.value.includes(selectedMethod.value)) {
        selectedMethod.value = firstMethod
      }
    }
    
    pathParams.value = {}
  })

  return {
    selectedEndpoint,
    selectedMethod,
    pathParams,
    formData,
    allOperations,
    endpointOptions,
    availableMethods,
    pathParameterNames,
    currentEndpointResource,
    writableProperties,
  }
}

