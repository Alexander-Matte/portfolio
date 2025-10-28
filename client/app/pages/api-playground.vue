<script setup lang="ts">
import type { ApiEndpointsResponse } from '~/types/api'
import { storeToRefs } from 'pinia'

useSeoMeta({
  title: 'API Playground - Alexander Matte',
  description: 'Interactive API Platform demonstration showcasing Symfony backend development skills with real-time features.',
})

const sessionStore = useSessionStore()
const { buildApiUrl } = useApi()

// Use storeToRefs to maintain reactivity when destructuring
const { username, isAuthenticated: isConnected, token } = storeToRefs(sessionStore)

const { data: endpointsData, refresh: refreshEndpoints, pending: endpointsPending, error: endpointsError } = await useFetch(
  buildApiUrl('/api/endpoints'),
  {
    headers: computed(() => ({
      'Accept': 'application/ld+json',
      ...(token.value && { 'Authorization': `Bearer ${token.value}` })
    })),
    watch: [token]
  }
)


const {
  selectedEndpoint,
  selectedMethod,
  pathParams,
  formData,
  endpointOptions,
  availableMethods,
  pathParameterNames,
  writableProperties,
} = useApiExplorer(endpointsData as Ref<ApiEndpointsResponse | null>)

const { executeRequest, responseData, responseTime, isLoading } = useApiRequest()

const { createSampleTask, createSampleNote } = useQuickActions(
  selectedEndpoint,
  selectedMethod,
  formData
)

// Clear response data when endpoint changes
watch(selectedEndpoint, () => {
  responseData.value = null
  responseTime.value = null
})

watch(selectedMethod, () => {
  responseData.value = null
  responseTime.value = null
})

const { 
  requestCount, 
  avgResponseTime, 
  pending: statsPending,
  refresh: refreshStats
} = useUserStats()

const realtimeUpdates = ref<any[]>([])

const handleExecute = async () => {
  await executeRequest({
    endpoint: selectedEndpoint.value,
    method: selectedMethod.value,
    pathParams: pathParams.value,
    pathParameterNames: pathParameterNames.value,
    formData: formData.value,
    token: token.value,
  })
  // Refresh stats after request to update the view
  await refreshStats()
}

const logoutModalOpen = ref(false);

const logoutAndClearData = () => {
  sessionStore.clearSession()
  responseData.value = null
  responseTime.value = null
  logoutModalOpen.value = false
}

const login = async() => {
  await sessionStore.initializeSession()
  await refreshEndpoints()
}
</script>

<template>
  <div class="relative">
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
            <div class="flex items-center gap-4">
              <div class="p-3 bg-gradient-to-br from-green-500 to-blue-600 rounded-xl">
                <UIcon name="i-heroicons-user-circle" class="text-3xl text-white" />
              </div>
              <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                  {{ username ? 'Logged in as' : 'Not logged in' }}
                </p>
                <p class="font-bold text-lg">
                  {{ username || 'Unauthenticated' }}
                </p>
              </div>
            </div>

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
                v-if="!isConnected"
                label="Login"
                color="primary"
                variant="subtle"
                @click="login"
              />
              <UModal
                v-else
                title="Logout and Clear My Data"
                v-model:open="logoutModalOpen"
              >
                <UButton label="Logout and Clear My Data"
                 color="error" 
                 variant="subtle" 
                 />

                <template #body>
                  <div class="rounded-xl p-4 space-y-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">
                      <span class="font-semibold text-red-600 dark:text-red-500">Warning:<br></span>
                      By accepting, you will be logged out and all your data will be cleared. 
                      <span class="font-medium">This action cannot be undone.</span>
                      To continue to interact with the API, you will need to log in again.
                    </p>
                  </div>

                </template>
                <template #footer>
                <div class="flex justify-center w-full">
                  <UButton
                    color="error"
                    variant="soft"
                    icon="i-heroicons-trash"
                    @click="logoutAndClearData"
                  >
                    Logout and Clear My Data
                  </UButton>
                </div>
              </template>


              </UModal>
            </div>
          </div>

          <UAlert
            color="warning"
            variant="soft"
            icon="i-heroicons-information-circle"
            title="Temporary Playground"
            description="All data is automatically purged daily at midnight UTC. Feel free to experiment!
            For security reasons, do not post sensitive data
            "
            class="mt-4"
          />
        </UCard>
      </section>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-6">
          <ApiPlaygroundApiRequestForm
            v-model:selected-endpoint="selectedEndpoint"
            v-model:selected-method="selectedMethod"
            v-model:path-params="pathParams"
            v-model:form-data="formData"
            :endpoint-options="endpointOptions"
            :available-methods="availableMethods"
            :path-parameter-names="pathParameterNames"
            :writable-properties="writableProperties"
            :is-loading="isLoading"
            :is-authenticated="isConnected"
            @execute="handleExecute"
          />
          
          <ApiPlaygroundApiResponseDisplay
            :response-data="responseData"
            :response-time="responseTime"
            :is-authenticated="isConnected"
          />
          
          <UCard
          v-if="isConnected"
          >
            <template #header>
              <div class="flex items-center gap-2">
                <UIcon name="i-heroicons-book-open" class="text-2xl text-primary" />
                <h2 class="text-2xl font-bold">Available Endpoints</h2>
              </div>
            </template>

            <ApiEndpointsList 
              :endpoints-data="endpointsData as ApiEndpointsResponse | null" 
              :pending="endpointsPending"
              :error="endpointsError"
              :refresh="refreshEndpoints"
            />

          </UCard>
        </div>

        <div class="space-y-6">
          <ApiPlaygroundApiStatsPanel 
            :request-count="requestCount"
            :avg-response-time="avgResponseTime"
          />

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

          <ApiPlaygroundApiQuickActions
            :is-authenticated="isConnected"
            @create-sample-task="createSampleTask"
            @create-sample-note="createSampleNote"
            @refresh-data="refreshStats"
          />

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
