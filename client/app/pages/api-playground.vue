<script setup lang="ts">
import type { ApiEndpointsResponse, ApiOperation } from '~/types/api'

useSeoMeta({
  title: 'API Playground - Alexander Matte',
  description: 'Interactive API Platform demonstration showcasing Symfony backend development skills with real-time features.',
})

const sessionStore = useSessionStore()
const { buildApiUrl } = useApi()

const username = computed(() => sessionStore.username)
const isConnected = computed(() => sessionStore.isAuthenticated)

// Fetch available endpoints
const { data: endpointsData } = await useFetch<ApiEndpointsResponse>(
  buildApiUrl('/api/endpoints'),
  {
    headers: {
      'accept': 'application/ld+json'
    }
  }
)

// Flatten all operations into a single list with unique paths
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

// Create endpoint options for the dropdown (unique paths)
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

// Get available methods for the selected endpoint
const availableMethods = computed((): Array<'GET' | 'POST' | 'PUT' | 'PATCH' | 'DELETE'> => {
  if (!selectedEndpoint.value) return []
  
  return allOperations.value
    .filter(op => op.path === selectedEndpoint.value)
    .map(op => op.method)
    .filter((method): method is 'GET' | 'POST' | 'PUT' | 'PATCH' | 'DELETE' => method !== 'UNKNOWN')
})

const selectedEndpoint = ref<string>('/api/endpoints')
const selectedMethod = ref<'GET' | 'POST' | 'PUT' | 'PATCH' | 'DELETE'>('GET')

// Path parameters handling
const pathParams = ref<Record<string, string>>({})

// Extract path parameters from endpoint (e.g., {id}, {uuid})
const pathParameterNames = computed(() => {
  if (!selectedEndpoint.value) return []
  const matches = selectedEndpoint.value.match(/\{([^}]+)\}/g)
  if (!matches) return []
  return matches.map(match => match.slice(1, -1)) // Remove { and }
})

// Watch for endpoint changes and update method if needed
watch(selectedEndpoint, (newEndpoint) => {
  if (newEndpoint && availableMethods.value.length > 0) {
    // If current method is not available for new endpoint, select the first available method
    const firstMethod = availableMethods.value[0]
    if (firstMethod && !availableMethods.value.includes(selectedMethod.value)) {
      selectedMethod.value = firstMethod
    }
  }
  
  // Reset path parameters when endpoint changes
  pathParams.value = {}
})

const requestBody = ref<string>('')
const responseData = ref<any>(null)
const responseTime = ref<number | null>(null)
const isLoading = ref(false)

const realtimeUpdates = ref<any[]>([])

// TODO: Implement API calls
// TODO: Implement Mercure connection
// TODO: Implement data purge

const executeRequest = async () => {
  isLoading.value = true
  responseData.value = null
  responseTime.value = null

  try {
    const startTime = Date.now()
    
    // Replace path parameters in the endpoint URL
    let finalEndpoint = selectedEndpoint.value
    for (const paramName of pathParameterNames.value) {
      const paramValue = pathParams.value[paramName]
      if (!paramValue || paramValue.trim() === '') {
        responseData.value = { error: `Missing required path parameter: ${paramName}` }
        isLoading.value = false
        return
      }
      finalEndpoint = finalEndpoint.replace(`{${paramName}}`, encodeURIComponent(paramValue))
    }
    
    // Prepare headers
    const headers: Record<string, string> = {
      'Accept': 'application/ld+json',
    }
    
    // Add authentication token if available
    if (sessionStore.token) {
      headers['Authorization'] = `Bearer ${sessionStore.token}`
    }
    
    // Prepare request options
    const options: any = {
      method: selectedMethod.value,
      headers,
    }
    
    // Add body for POST, PUT, PATCH requests
    if (['POST', 'PUT', 'PATCH'].includes(selectedMethod.value) && requestBody.value.trim()) {
      headers['Content-Type'] = 'application/ld+json'
      try {
        options.body = JSON.parse(requestBody.value)
      } catch (e) {
        responseData.value = { error: 'Invalid JSON in request body' }
        isLoading.value = false
        return
      }
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
</script>

<template>
  <div class="relative">
    <!-- Decorative background -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
      <div class="absolute -top-40 -right-40 w-80 h-80 bg-green-400/20 dark:bg-green-600/10 rounded-full blur-3xl"></div>
      <div class="absolute top-80 -left-40 w-96 h-96 bg-blue-400/20 dark:bg-blue-600/10 rounded-full blur-3xl"></div>
    </div>

    <UContainer class="relative py-12">
      <section class="mb-12">
        <div class="text-center space-y-4">
          <UBadge color="success" variant="subtle" size="lg" class="shadow-lg">
            <span class="flex items-center gap-2">
              <UIcon name="i-heroicons-beaker" />
              Interactive Demo
            </span>
          </UBadge>
          
          <h1 class="text-4xl md:text-6xl font-bold bg-gradient-to-r from-green-600 via-blue-600 to-purple-600 bg-clip-text text-transparent">
            API Playground
          </h1>
          
          <p class="text-xl text-gray-600 dark:text-gray-400 max-w-3xl mx-auto">
            Experience my Symfony + API Platform backend in action. Create, read, update, and delete data with real-time updates powered by Mercure.
          </p>
        </div>
      </section>

      <section class="mb-8">
        <UCard class="backdrop-blur-sm bg-white/80 dark:bg-gray-900/80">
          <div class="flex flex-col md:flex-row items-center justify-between gap-4">
            <!-- User Info -->
            <div class="flex items-center gap-4">
              <div class="p-3 bg-gradient-to-br from-green-500 to-blue-600 rounded-xl">
                <UIcon name="i-heroicons-user-circle" class="text-3xl text-white" />
              </div>
              <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Logged in as</p>
                <p class="font-bold text-lg">
                  {{ username || 'Loading...' }}
                </p>
              </div>
            </div>

            <!-- Status Indicators -->
            <div class="flex items-center gap-4">
              <div class="flex items-center gap-2">
                <div :class="[
                  'w-2 h-2 rounded-full',
                  isConnected ? 'bg-green-500 animate-pulse' : 'bg-red-500'
                ]"></div>
                <span class="text-sm">
                  {{ isConnected ? 'Connected' : 'Disconnected' }}
                </span>
              </div>

              <UButton
                color="error"
                variant="soft"
                size="sm"
                icon="i-heroicons-trash"
              >
                Clear My Data
              </UButton>
            </div>
          </div>

          <UAlert
            color="warning"
            variant="soft"
            icon="i-heroicons-information-circle"
            title="Temporary Playground"
            description="All data is automatically purged daily at midnight UTC. Feel free to experiment!"
            class="mt-4"
          />
        </UCard>
      </section>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-6">
          <UCard>
            <template #header>
              <div class="flex items-center gap-2">
                <UIcon name="i-heroicons-server" class="text-2xl text-primary" />
                <h2 class="text-2xl font-bold">API Explorer</h2>
              </div>
            </template>

            <div class="space-y-4">
              <div class="flex gap-2">
                <USelect
                  v-model="selectedMethod"
                  :items="availableMethods"
                  :disabled="availableMethods.length === 0"
                  class="w-32"
                />
                <USelect
                  v-model="selectedEndpoint"
                  :items="endpointOptions"
                  placeholder="Select an endpoint..."
                  value-attribute="value"
                  option-attribute="label"
                  class="flex-1"
                />
              </div>

              <!-- Path Parameters -->
              <div v-if="pathParameterNames.length > 0" class="space-y-3">
                <div class="text-sm font-medium text-gray-700 dark:text-gray-300">
                  Path Parameters
                </div>
                <div v-for="paramName in pathParameterNames" :key="paramName" class="space-y-1">
                  <label class="block text-sm text-gray-600 dark:text-gray-400">
                    {{ paramName }}
                  </label>
                  <UInput
                    v-model="pathParams[paramName]"
                    :placeholder="`Enter ${paramName}`"
                    size="lg"
                  />
                </div>
              </div>

              <div v-if="selectedMethod === 'POST' || selectedMethod === 'PUT' || selectedMethod === 'PATCH'" class="w-full">
                <label class="block text-sm font-medium mb-2">Request Body (JSON)</label>
                <UTextarea
                  v-model="requestBody"
                  :rows="8"
                  placeholder='{\n  "name": "Example",\n  "description": "Test data"\n}'
                  class="font-mono text-sm w-full"
                />
              </div>

              <UButton
                block
                size="xl"
                icon="i-heroicons-play"
                :loading="isLoading"
                :disabled="!selectedEndpoint"
                @click="executeRequest"
              >
                Execute Request
              </UButton>
            </div>
          </UCard>

          <UCard>
            <template #header>
              <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                  <UIcon name="i-heroicons-code-bracket" class="text-2xl text-primary" />
                  <h2 class="text-2xl font-bold">Response</h2>
                </div>
                <div v-if="responseTime" class="text-sm text-gray-500">
                  {{ responseTime }}ms
                </div>
              </div>
            </template>

            <div class="min-h-[300px]">
              <div v-if="!responseData" class="flex items-center justify-center h-[300px] text-gray-400">
                <div class="text-center space-y-2">
                  <UIcon name="i-heroicons-arrow-up-circle" class="text-5xl" />
                  <p>Execute a request to see the response</p>
                </div>
              </div>

              <pre v-else class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg overflow-auto text-sm">{{ responseData }}
              </pre>
            </div>
          </UCard>

          <UCard>
            <template #header>
              <div class="flex items-center gap-2">
                <UIcon name="i-heroicons-book-open" class="text-2xl text-primary" />
                <h2 class="text-2xl font-bold">Available Endpoints</h2>
              </div>
            </template>

            <ApiEndpointsList />

            <!-- <UAccordion :items="[
              {
                label: 'Tasks API',
                icon: 'i-heroicons-clipboard-document-list',
                defaultOpen: true,
                slot: 'tasks'
              },
              {
                label: 'Notes API',
                icon: 'i-heroicons-document-text',
                slot: 'notes'
              },
              {
                label: 'Real-time Events',
                icon: 'i-heroicons-bolt',
                slot: 'realtime'
              }
            ]">
              <template #tasks>
                <div class="space-y-3 p-4">
                  <div class="flex items-start gap-3">
                    <UBadge color="green" variant="subtle">GET</UBadge>
                    <div>
                      <code class="text-sm">/api/tasks</code>
                      <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Get all your tasks</p>
                    </div>
                  </div>
                  <div class="flex items-start gap-3">
                    <UBadge color="blue" variant="subtle">POST</UBadge>
                    <div>
                      <code class="text-sm">/api/tasks</code>
                      <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Create a new task</p>
                    </div>
                  </div>
                  <div class="flex items-start gap-3">
                    <UBadge color="amber" variant="subtle">PUT</UBadge>
                    <div>
                      <code class="text-sm">/api/tasks/{'{id}'}</code>
                      <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Update a task</p>
                    </div>
                  </div>
                  <div class="flex items-start gap-3">
                    <UBadge color="red" variant="subtle">DELETE</UBadge>
                    <div>
                      <code class="text-sm">/api/tasks/{'{id}'}</code>
                      <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Delete a task</p>
                    </div>
                  </div>
                </div>
              </template>

              <template #notes>
                <div class="space-y-3 p-4">
                  <div class="flex items-start gap-3">
                    <UBadge color="green" variant="subtle">GET</UBadge>
                    <div>
                      <code class="text-sm">/api/notes</code>
                      <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Get all your notes</p>
                    </div>
                  </div>
                  <div class="flex items-start gap-3">
                    <UBadge color="blue" variant="subtle">POST</UBadge>
                    <div>
                      <code class="text-sm">/api/notes</code>
                      <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Create a new note</p>
                    </div>
                  </div>
                </div>
              </template>

              <template #realtime>
                <div class="p-4">
                  <p class="text-sm text-gray-600 dark:text-gray-400">
                    Real-time updates are powered by <span class="font-semibold">Mercure</span>. 
                    Any changes made by you or others will appear instantly in the activity feed.
                  </p>
                </div>
              </template>
            </UAccordion> -->
          </UCard>
        </div>

        <div class="space-y-6">
          <UCard>
            <template #header>
              <div class="flex items-center gap-2">
                <UIcon name="i-heroicons-chart-bar" class="text-2xl text-primary" />
                <h3 class="text-xl font-bold">API Stats</h3>
              </div>
            </template>

            <div class="space-y-4">
              <div>
                <div class="flex justify-between text-sm mb-1">
                  <span class="text-gray-600 dark:text-gray-400">Requests Made</span>
                  <span class="font-bold">0</span>
                </div>
                <UProgress :value="0" />
              </div>

              <div>
                <div class="flex justify-between text-sm mb-1">
                  <span class="text-gray-600 dark:text-gray-400">Avg Response Time</span>
                  <span class="font-bold">-- ms</span>
                </div>
              </div>

              <div>
                <div class="flex justify-between text-sm mb-1">
                  <span class="text-gray-600 dark:text-gray-400">Success Rate</span>
                  <span class="font-bold">--%</span>
                </div>
                <UProgress :value="0" color="success" />
              </div>
            </div>
          </UCard>

          <UCard>
            <template #header>
              <div class="flex items-center gap-2">
                <UIcon name="i-heroicons-bolt" class="text-2xl text-amber-500" />
                <h3 class="text-xl font-bold">Live Activity</h3>
              </div>
            </template>

            <div class="space-y-3 max-h-[400px] overflow-y-auto">
              <div v-if="realtimeUpdates.length === 0" class="text-center py-8 text-gray-400">
                <UIcon name="i-heroicons-signal" class="text-4xl mb-2" />
                <p class="text-sm">Waiting for real-time updates...</p>
              </div>

              <div
                v-for="update in realtimeUpdates"
                :key="update.id"
                class="p-3 bg-gray-50 dark:bg-gray-800 rounded-lg"
              ></div>
            </div>
          </UCard>

          <UCard>
            <template #header>
              <div class="flex items-center gap-2">
                <UIcon name="i-heroicons-bolt" class="text-2xl text-primary" />
                <h3 class="text-xl font-bold">Quick Actions</h3>
              </div>
            </template>

            <div class="space-y-2">
              <UButton
                block
                color="neutral"
                variant="soft"
                icon="i-heroicons-plus"
              >
                Create Sample Task
              </UButton>
              <UButton
                block
                color="neutral"
                variant="soft"
                icon="i-heroicons-document-plus"
              >
                Create Sample Note
              </UButton>
              <UButton
                block
                color="neutral"
                variant="soft"
                icon="i-heroicons-arrow-path"
              >
                Refresh Data
              </UButton>
            </div>
          </UCard>

          <UCard>
            <template #header>
              <div class="flex items-center gap-2">
                <UIcon name="i-heroicons-cube" class="text-2xl text-primary" />
                <h3 class="text-xl font-bold">Powered By</h3>
              </div>
            </template>

            <div class="space-y-3">
              <div class="flex items-center gap-3">
                <UIcon name="i-skill-icons-symfony-dark" class="text-2xl" />
                <span class="text-sm">Symfony 7</span>
              </div>
              <div class="flex items-center gap-3">
                <UIcon name="i-logos-api-platform" class="text-2xl" />
                <span class="text-sm">API Platform</span>
              </div>
              <div class="flex items-center gap-3">
                <UIcon name="i-skill-icons-postgresql-dark" class="text-2xl" />
                <span class="text-sm">PostgreSQL</span>
              </div>
              <div class="flex items-center gap-3">
                <UIcon name="i-heroicons-bolt" class="text-2xl text-amber-500" />
                <span class="text-sm">Mercure (Real-time)</span>
              </div>
            </div>
          </UCard>
        </div>
      </div>
    </UContainer>
  </div>
</template>

