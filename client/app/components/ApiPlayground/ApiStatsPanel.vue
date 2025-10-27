<script setup lang="ts">
defineProps<{
  requestCount: number
  avgResponseTime: number
}>()
</script>

<template>
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
          <span class="font-bold">{{ requestCount }}</span>
        </div>
        <UProgress :value="Math.min(requestCount * 5, 100)" />
      </div>

      <div>
        <div class="flex justify-between text-sm mb-1">
          <span class="text-gray-600 dark:text-gray-400">Avg Response Time</span>
          <span class="font-bold">{{ avgResponseTime }} ms</span>
        </div>
      </div>
    </div>
  </UCard>
</template>

