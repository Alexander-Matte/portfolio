<script setup lang="ts">
import type { NuxtError } from '#app'

const props = defineProps<{
  error: NuxtError
}>()

// Customize error messages based on status code
const getErrorDetails = (error: NuxtError) => {
  switch (error.statusCode) {
    case 404:
      return {
        statusMessage: 'Page Not Found',
        message: 'The page you are looking for doesn\'t exist or has been moved.',
        icon: 'i-heroicons-magnifying-glass'
      }
    case 500:
      return {
        statusMessage: 'Internal Server Error',
        message: 'Something went wrong on our end. Please try again later.',
        icon: 'i-heroicons-exclamation-triangle'
      }
    default:
      return {
        statusMessage: error.statusMessage || 'An Error Occurred',
        message: error.message || 'Something unexpected happened.',
        icon: 'i-heroicons-exclamation-circle'
      }
  }
}

const errorDetails = getErrorDetails(props.error)
</script>

<template>
  <NuxtLayout>
    <div class="relative min-h-[calc(100vh-var(--ui-header-height)-var(--ui-footer-height))]">
      <!-- Decorative background elements (matching your portfolio style) -->
      <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-blue-400/20 dark:bg-blue-600/10 rounded-full blur-3xl"></div>
        <div class="absolute top-80 -left-40 w-96 h-96 bg-purple-400/20 dark:bg-purple-600/10 rounded-full blur-3xl"></div>
      </div>

      <UContainer class="relative">
        <UError
          :error="{
            statusCode: error.statusCode,
            statusMessage: errorDetails.statusMessage,
            message: errorDetails.message
          }"
          :clear="{
            color: 'primary',
            size: 'xl',
            icon: 'i-heroicons-arrow-left',
            label: 'Back to Home',
            class: 'shadow-lg hover:shadow-xl transition-all'
          }"
          redirect="/"
        >
          <template #statusCode>
            <div class="flex flex-col items-center gap-4">
              <div class="p-4 bg-gradient-to-br from-red-500 to-orange-600 rounded-2xl shadow-xl">
                <UIcon :name="errorDetails.icon" class="text-6xl text-white" />
              </div>
              <UBadge color="red" variant="soft" size="lg" class="shadow-md">
                Error {{ error.statusCode }}
              </UBadge>
            </div>
          </template>

          <template #statusMessage>
            <h1 class="text-4xl md:text-5xl font-bold bg-gradient-to-r from-blue-600 via-purple-600 to-blue-600 bg-clip-text text-transparent mt-6">
              {{ errorDetails.statusMessage }}
            </h1>
          </template>

          <template #message>
            <p class="text-xl text-gray-600 dark:text-gray-400 max-w-2xl mx-auto mt-4">
              {{ errorDetails.message }}
            </p>
          </template>

          <template #links>
            <div class="flex flex-col sm:flex-row gap-4 items-center justify-center mt-8">
              <UButton
                to="/"
                color="primary"
                size="xl"
                icon="i-heroicons-home"
                class="shadow-lg hover:shadow-xl transition-all"
              >
                Go Home
              </UButton>
              <UButton
                to="/#contact"
                color="gray"
                variant="outline"
                size="xl"
                icon="i-heroicons-envelope"
                class="shadow-lg hover:shadow-xl transition-all"
              >
                Contact Support
              </UButton>
            </div>
          </template>
        </UError>
      </UContainer>
    </div>
  </NuxtLayout>
</template>

