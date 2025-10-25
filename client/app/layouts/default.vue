<script setup lang="ts">
const route = useRoute()
</script>

<template>
  <UApp>
    <UHeader>
      <template #left>
        <NuxtLink to="/" class="flex items-center gap-2 font-bold text-xl">
          <UIcon name="i-heroicons-code-bracket" class="text-2xl text-primary" />
          <span class="hidden sm:inline">Alexander Matte</span>
          <span class="sm:hidden">AM</span>
        </NuxtLink>
      </template>

      <template #right>
        <UButton
          to="/#skills"
          color="gray"
          variant="ghost"
          class="hidden md:inline-flex"
        >
          Skills
        </UButton>
        <UButton
          to="/api-playground"
          color="gray"
          variant="ghost"
          class="hidden md:inline-flex"
        >
          API Playground
        </UButton>
        <UButton
          to="/#contact"
          color="gray"
          variant="ghost"
          class="hidden md:inline-flex"
        >
          Contact
        </UButton>
        
        <UColorModeButton />

        <UButton
          to="https://github.com/Alexander-Matte"
          target="_blank"
          external
          color="gray"
          variant="ghost"
          icon="i-simple-icons-github"
          aria-label="GitHub"
        />
      </template>

      <template #body>
        <div class="flex flex-col gap-2">
          <UButton
            to="/#skills"
            color="gray"
            variant="ghost"
            block
          >
            Skills
          </UButton>
          <UButton
            to="/api-playground"
            color="gray"
            variant="ghost"
            block
          >
            API Playground
          </UButton>
          <UButton
            to="/#contact"
            color="gray"
            variant="ghost"
            block
          >
            Contact
          </UButton>
          <UButton
            to="https://github.com/Alexander-Matte"
            target="_blank"
            external
            color="gray"
            variant="ghost"
            icon="i-simple-icons-github"
            block
          >
            GitHub
          </UButton>
          <UButton
            to="https://www.linkedin.com/in/alex-matte71/"
            target="_blank"
            external
            color="gray"
            variant="ghost"
            icon="i-simple-icons-linkedin"
            block
          >
            LinkedIn
          </UButton>
        </div>
      </template>
    </UHeader>

    <UMain>
      <slot />
    </UMain>

    <UFooter>
      <template #left>
        <p class="text-sm text-gray-600 dark:text-gray-400">
          © 2025 Alexander Matte
        </p>
      </template>

      <template #right>
        <div class="flex items-center gap-4">
          <UButton
            to="https://github.com/Alexander-Matte"
            target="_blank"
            external
            color="gray"
            variant="ghost"
            icon="i-simple-icons-github"
            size="sm"
            aria-label="GitHub"
          />
          <UButton
            to="https://www.linkedin.com/in/alex-matte71/"
            target="_blank"
            external
            color="gray"
            variant="ghost"
            icon="i-simple-icons-linkedin"
            size="sm"
            aria-label="LinkedIn"
          />
          <UButton
            to="mailto:alex.matte71@gmail.com"
            external
            color="gray"
            variant="ghost"
            icon="i-heroicons-envelope"
            size="sm"
            aria-label="Email"
          />
        </div>
      </template>
    </UFooter>
  </UApp>
</template>

