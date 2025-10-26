<script setup lang="ts">
import type { ApiEndpointsResponse, ApiOperation } from '~/types/api'

const { buildApiUrl } = useApi()

// Fetch endpoints from API
const { data, pending, error, refresh } = await useFetch<ApiEndpointsResponse>(
  buildApiUrl('/api/endpoints'),
  {
    headers: {
      'accept': 'application/ld+json'
    }
  }
)

// Extract the endpoints array from the response
const endpoints = computed(() => data.value?.endpoints || [])

// Badge color and variant types
type BadgeColor = 'primary' | 'secondary' | 'success' | 'info' | 'warning' | 'error' | 'neutral'
type BadgeVariant = 'solid' | 'outline' | 'soft' | 'subtle'

// Helper to get badge styling based on HTTP method
const getMethodStyle = (method: ApiOperation['method']): { color: BadgeColor; variant: BadgeVariant } => {
  const styles: Record<ApiOperation['method'], { color: BadgeColor; variant: BadgeVariant }> = {
    GET: { color: 'primary', variant: 'solid' },
    POST: { color: 'success', variant: 'soft' },
    PUT: { color: 'warning', variant: 'solid' },
    PATCH: { color: 'info', variant: 'solid' },
    DELETE: { color: 'error', variant: 'solid' },
    UNKNOWN: { color: 'neutral', variant: 'subtle' }
  }
  return styles[method] || { color: 'neutral', variant: 'subtle' }
}
</script>

<template>
  <div class="space-y-4">
    <!-- Loading State -->
    <div v-if="pending" class="flex items-center justify-center py-8">
      <UIcon name="i-heroicons-arrow-path" class="animate-spin text-2xl" />
      <span class="ml-2">Loading endpoints...</span>
    </div>

    <!-- Error State -->
    <UAlert
      v-else-if="error"
      color="error"
      variant="soft"
      title="Failed to load endpoints"
      :description="error.message"
    >
      <template #actions>
        <UButton color="error" variant="soft" @click="() => refresh()">
          Retry
        </UButton>
      </template>
    </UAlert>

    <!-- Endpoints List -->
    <div v-else-if="endpoints.length > 0" class="space-y-4">
      <UCard
        v-for="(endpoint, index) in endpoints"
        :key="`${endpoint.name}-${index}`"
        class="hover:shadow-lg transition-shadow"
      >
        <template #header>
          <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
            {{ endpoint.name }}
          </h3>
        </template>

        <div class="space-y-3">
          <div
            v-for="(operation, opIndex) in endpoint.operations"
            :key="`${operation.method}-${operation.path}-${opIndex}`"
            class="flex items-center gap-4 p-3 rounded-lg bg-gray-50 dark:bg-gray-800/50"
          >
            <UBadge
              :color="getMethodStyle(operation.method).color"
              :variant="getMethodStyle(operation.method).variant"
              size="lg"
              class="shrink-0 font-bold min-w-[70px] justify-center"
            >
              {{ operation.method }}
            </UBadge>
            
            <div class="flex-1 min-w-0">
              <code class="text-sm font-semibold text-gray-900 dark:text-gray-100 break-all">
                {{ operation.path }}
              </code>
              <p v-if="operation.description" class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                {{ operation.description }}
              </p>
            </div>
          </div>
        </div>
      </UCard>
    </div>

    <!-- Empty State -->
    <UAlert
      v-else
      color="neutral"
      variant="soft"
      title="No endpoints found"
      description="The API doesn't have any registered endpoints yet."
    />
  </div>
</template>

