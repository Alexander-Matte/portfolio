<script setup lang="ts">
const emit = defineEmits<{
  'create-sample-task': []
  'create-sample-note': []
  'refresh-data': []
}>()
</script>

<template>
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
        @click="emit('create-sample-task')"
      >
        Create Sample Task
      </UButton>

      <UButton
        block
        color="neutral"
        variant="soft"
        icon="i-heroicons-document-plus"
        @click="emit('create-sample-note')"
      >
        Create Sample Note
      </UButton>
      <UButton
        block
        color="neutral"
        variant="soft"
        icon="i-heroicons-arrow-path"
        @click="emit('refresh-data')"
      >
        Refresh Data
      </UButton>
    </div>
  </UCard>
</template>

