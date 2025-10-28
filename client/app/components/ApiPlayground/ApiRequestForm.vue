<script setup lang="ts">
import type { ApiProperty } from '~/types/api'

const props = defineProps<{
  selectedEndpoint: string
  selectedMethod: 'GET' | 'POST' | 'PUT' | 'PATCH' | 'DELETE'
  endpointOptions: Array<{ label: string; value: string }>
  availableMethods: Array<'GET' | 'POST' | 'PUT' | 'PATCH' | 'DELETE'>
  pathParams: Record<string, string>
  pathParameterNames: string[]
  formData: Record<string, any>
  writableProperties: ApiProperty[]
  isLoading: boolean
  isAuthenticated: boolean
}>()

const emit = defineEmits<{
  'update:selectedEndpoint': [value: string]
  'update:selectedMethod': [value: 'GET' | 'POST' | 'PUT' | 'PATCH' | 'DELETE']
  'update:pathParams': [value: Record<string, string>]
  'update:formData': [value: Record<string, any>]
  'execute': []
}>()

const localEndpoint = computed({
  get: () => props.selectedEndpoint,
  set: (value) => emit('update:selectedEndpoint', value)
})

const localMethod = computed({
  get: () => props.selectedMethod,
  set: (value) => emit('update:selectedMethod', value)
})

const localPathParams = computed({
  get: () => props.pathParams,
  set: (value) => emit('update:pathParams', value)
})

const localFormData = computed({
  get: () => props.formData,
  set: (value) => emit('update:formData', value)
})
</script>

<template>
  <UCard>
    <template #header>
      <div class="flex items-center gap-2">
        <UIcon name="i-heroicons-server" class="text-2xl text-primary" />
        <h2 class="text-2xl font-bold">API Explorer</h2>
      </div>
    </template>

    <!-- Show message when not authenticated -->
    <div v-if="!props.isAuthenticated" class="py-16 text-center">
      <UIcon name="i-heroicons-lock-closed" class="text-6xl text-gray-400 mb-4" />
      <p class="text-lg text-gray-700 dark:text-gray-300 font-medium mb-2">
        Please login to use the API Explorer
      </p>
      <p class="text-sm text-gray-500 dark:text-gray-400">
        Authentication is required to interact with the API
      </p>
    </div>

    <div v-else class="space-y-6">
      <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
          Request Configuration
        </label>
        <div class="flex gap-2">
          <USelect
            v-model="localMethod"
            :items="availableMethods"
            :disabled="availableMethods.length === 0"
            class="w-32"
          />
          <USelect
            v-model="localEndpoint"
            :items="endpointOptions"
            placeholder="Select an endpoint..."
            value-attribute="value"
            option-attribute="label"
            class="flex-1"
          />
        </div>
      </div>

      <div v-if="pathParameterNames.length > 0" class="bg-gray-50 dark:bg-gray-800/50 rounded-lg p-4 space-y-3">
        <div class="flex items-center gap-2">
          <UIcon name="i-heroicons-variable" class="text-primary" />
          <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Path Parameters</span>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
          <div v-for="paramName in pathParameterNames" :key="paramName" class="space-y-1">
            <label class="block text-sm text-gray-600 dark:text-gray-400">
              {{ paramName }}
            </label>
            <UInput
              v-model="localPathParams[paramName]"
              :placeholder="`Enter ${paramName}`"
              size="lg"
            />
          </div>
        </div>
      </div>

      <div v-if="selectedMethod === 'POST' || selectedMethod === 'PUT' || selectedMethod === 'PATCH'" class="bg-gray-50 dark:bg-gray-800/50 rounded-lg p-4 space-y-4">
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-2">
            <UIcon name="i-heroicons-document-text" class="text-primary" />
            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Request Data</span>
          </div>
          <UBadge v-if="writableProperties.length > 0" color="primary" variant="subtle">
            {{ writableProperties.length }} field{{ writableProperties.length !== 1 ? 's' : '' }}
          </UBadge>
        </div>

        <div v-if="writableProperties.length === 0" class="text-center py-8 text-gray-400">
          <UIcon name="i-heroicons-information-circle" class="text-3xl mb-2" />
          <p class="text-sm">No writable fields available for this endpoint</p>
        </div>

        <div v-else class="grid grid-cols-1 gap-4 w-full">
          <div 
            v-for="property in writableProperties" 
            :key="property.name"
            class="space-y-2"
          >
            <label class="flex items-center gap-2 text-sm font-medium text-gray-700 dark:text-gray-300">
              {{ property.name }}
              <UBadge v-if="property.required" color="error" variant="soft" size="xs">required</UBadge>
              <UBadge color="neutral" variant="subtle" size="xs">{{ property.type }}</UBadge>
            </label>

            <UTextarea
              v-if="property.type === 'string' && ['description', 'content'].includes(property.name)"
              v-model="localFormData[property.name]"
              :placeholder="`Enter ${property.name}...`"
              :rows="3"
              class="w-full"
              size="lg"
            />
            <UInput
              v-else-if="property.type === 'string'"
              v-model="localFormData[property.name]"
              :placeholder="`Enter ${property.name}...`"
              class="w-full"
              size="lg"
            />
            <UInput
              v-else-if="property.type === 'int'"
              v-model.number="localFormData[property.name]"
              type="number"
              :placeholder="`Enter ${property.name}...`"
              class="w-full"
              size="lg"
            />
            <div v-else-if="property.type === 'bool'" class="flex items-center gap-3 pt-2">
              <UToggle
                v-model="localFormData[property.name]"
                size="lg"
              />
              <span class="text-sm text-gray-600 dark:text-gray-400">
                {{ localFormData[property.name] ? 'True' : 'False' }}
              </span>
            </div>
            <UInput
              v-else
              v-model="localFormData[property.name]"
              :placeholder="`Enter ${property.name}...`"
              size="lg"
              class="w-full"
            />
          </div>
        </div>
      </div>

      <UButton
        block
        size="xl"
        icon="i-heroicons-play"
        :loading="isLoading"
        :disabled="!selectedEndpoint"
        @click="emit('execute')"
      >
        Execute Request
      </UButton>
    </div>
  </UCard>
</template>

