<script setup lang="ts">
const props = defineProps<{
  responseData: any
  responseTime: number | null
  isAuthenticated: boolean
}>()

const formattedResponse = computed(() => {
  if (!props.responseData) return null
  try {
    return JSON.stringify(props.responseData, null, 2)
  } catch (error) {
    return String(props.responseData)
  }
})
</script>

<template>
  <UCard v-if="isAuthenticated">
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

      <pre v-else class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg overflow-auto text-sm">{{ formattedResponse }}</pre>
    </div>
  </UCard>
</template>

