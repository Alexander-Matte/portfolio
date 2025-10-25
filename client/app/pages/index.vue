<script setup lang="ts">
useSeoMeta({
  title: 'Alex\'s Portfolio',
  description: 'Portfolio of Alexander Matte, a software developer student specializing in Symfony/PHP backend development and Nuxt frontend development.',
  ogTitle: 'Alexander Matte - Software Developer Student',
  ogDescription: 'Portfolio of Alexander Matte, a software developer student from Baden-Württemberg, Germany.',
})
</script>

<template>
  <div class="relative">
    <!-- Decorative background elements -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
      <div class="absolute -top-40 -right-40 w-80 h-80 bg-blue-400/20 dark:bg-blue-600/10 rounded-full blur-3xl"></div>
      <div class="absolute top-80 -left-40 w-96 h-96 bg-purple-400/20 dark:bg-purple-600/10 rounded-full blur-3xl"></div>
    </div>

    <UContainer class="relative">
      <!-- Hero Section -->
      <section class="py-24 md:py-32">
        <div class="text-center space-y-8">
          <div class="inline-block animate-bounce">
            <UBadge color="primary" variant="subtle" size="lg" class="shadow-lg">
              <span class="flex items-center gap-2">
                <UIcon name="i-heroicons-sparkles" />
                Software Developer Student
              </span>
            </UBadge>
          </div>
          
          <h1 class="text-5xl md:text-7xl font-bold tracking-tight">
            Hi, I'm <span class="gradient-text animate-gradient">Alexander Matte</span>
          </h1>
          
          <p class="text-xl md:text-2xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto leading-relaxed">
            Junior Developer with hands-on experience in <span class="font-semibold text-blue-600 dark:text-blue-400">PHP development</span>, 
            <span class="font-semibold text-purple-600 dark:text-purple-400">API integration</span>, and web technologies. 
            Specializing in building middleware applications with <span class="font-semibold text-blue-600 dark:text-blue-400">Symfony</span> 
            and <span class="font-semibold text-green-600 dark:text-green-400">API Platform</span> for e-commerce operations.
          </p>
          
          <div class="flex gap-4 justify-center pt-6 flex-wrap">
            <UButton 
              size="xl" 
              icon="i-heroicons-beaker"
              to="/api-playground"
              class="shadow-lg hover:shadow-xl transition-shadow animate-pulse"
            >
              Try API Playground
            </UButton>
            <UButton 
              size="xl" 
              color="gray"
              variant="outline"
              icon="i-heroicons-code-bracket"
              to="#skills"
              class="shadow-lg hover:shadow-xl transition-shadow"
            >
              View Skills
            </UButton>
            <UButton 
              size="xl" 
              color="gray" 
              variant="ghost"
              icon="i-heroicons-envelope"
              to="#contact"
              class="shadow-lg hover:shadow-xl transition-shadow"
            >
              Get in Touch
            </UButton>
          </div>
        </div>
      </section>

      <!-- About Section -->
      <section class="py-16">
        <div class="max-w-4xl mx-auto">
          <UCard class="hover-lift backdrop-blur-sm bg-white/80 dark:bg-gray-900/80 border-2 border-gray-200 dark:border-gray-700">
            <template #header>
              <div class="flex items-center gap-3">
                <div class="p-2 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg">
                  <UIcon name="i-heroicons-user-circle" class="text-3xl text-white" />
                </div>
                <h2 class="text-3xl font-bold gradient-text">About Me</h2>
              </div>
            </template>
            
            <div class="space-y-4 text-lg text-gray-700 dark:text-gray-300 leading-relaxed">
              <p class="flex items-start gap-3">
                <UIcon name="i-heroicons-map-pin" class="text-2xl text-blue-500 mt-1 flex-shrink-0" />
                <span>
                  Originally from <span class="font-semibold text-red-600 dark:text-red-400">Canada</span>, I moved to Germany in 2020 to pursue a new chapter in my life. 
                  Coming from a background in <span class="font-semibold">Oil & Gas</span> and <span class="font-semibold">Aviation</span>, I made the bold decision to follow 
                  my passion for learning and problem-solving by transitioning into software development.
                </span>
              </p>
              <p class="flex items-start gap-3">
                <UIcon name="i-heroicons-academic-cap" class="text-2xl text-green-500 mt-1 flex-shrink-0" />
                <span>
                  In 2023, I began my apprenticeship (Ausbildung) with <span class="font-semibold text-blue-600 dark:text-blue-400">Allnatura</span>, 
                  a leading online e-commerce company, where I've been gaining hands-on experience in full-stack 
                  development. I'm set to complete my apprenticeship in <span class="font-semibold">2026</span>.
                </span>
              </p>
            </div>
          </UCard>
        </div>
      </section>

      <!-- Experience Section -->
      <section class="py-16">
        <div class="text-center mb-12">
          <h2 class="text-4xl md:text-5xl font-bold gradient-text mb-4">Professional Experience</h2>
          <p class="text-gray-600 dark:text-gray-400 text-lg">Real-world projects and achievements</p>
        </div>
        
        <div class="max-w-5xl mx-auto space-y-6">
          <UCard class="hover-lift backdrop-blur-sm bg-white/80 dark:bg-gray-900/80 border-2 border-blue-200 dark:border-blue-800">
            <template #header>
              <div class="flex items-center justify-between flex-wrap gap-4">
                <div class="flex items-center gap-4">
                  <div class="p-3 bg-gradient-to-br from-green-500 to-blue-600 rounded-xl shadow-lg">
                    <UIcon name="i-heroicons-briefcase" class="text-3xl text-white" />
                  </div>
                  <div>
                    <h3 class="text-2xl font-bold">Software Developer Apprentice</h3>
                    <p class="text-gray-600 dark:text-gray-400 flex items-center gap-2 mt-1">
                      <UIcon name="i-heroicons-building-office" />
                      Allnatura • E-Commerce
                    </p>
                  </div>
                </div>
                <UBadge color="primary" variant="soft" size="lg" class="shadow-md">
                  <span class="flex items-center gap-2">
                    <UIcon name="i-heroicons-calendar" />
                    2023 - 2026
                  </span>
                </UBadge>
              </div>
            </template>
          
          <div class="space-y-6">
            <!-- Replatforming Project -->
            <div>
              <h4 class="font-semibold text-lg mb-3 flex items-center gap-2">
                <UIcon name="i-heroicons-arrow-path" class="text-primary" />
                Replatforming Project (Scayle Platform)
              </h4>
              <ul class="space-y-2 ml-6">
                <li class="flex items-start gap-2">
                  <UIcon name="i-heroicons-check-circle" class="text-green-500 mt-1 flex-shrink-0" />
                  <span>Collaborated with Product Management lead on product data model design for entire e-commerce sortiment, providing technical recommendations and product analysis, then configuring Scayle and middleware to accept products based on final PDM specifications</span>
                </li>
                <li class="flex items-start gap-2">
                  <UIcon name="i-heroicons-check-circle" class="text-green-500 mt-1 flex-shrink-0" />
                  <span>Core team member developing middleware architecture using Symfony for seamless integration between Scayle SaaS platform and ERP system</span>
                </li>
                <li class="flex items-start gap-2">
                  <UIcon name="i-heroicons-check-circle" class="text-green-500 mt-1 flex-shrink-0" />
                  <span>Developed and maintained complex product import logic and data transformation pipelines</span>
                </li>
                <li class="flex items-start gap-2">
                  <UIcon name="i-heroicons-check-circle" class="text-green-500 mt-1 flex-shrink-0" />
                  <span>Implemented custom REST API endpoints and webhooks for system integrations</span>
                </li>
                <li class="flex items-start gap-2">
                  <UIcon name="i-heroicons-check-circle" class="text-green-500 mt-1 flex-shrink-0" />
                  <span>Created custom Symfony commands to optimize workflows and automate processes</span>
                </li>
                <li class="flex items-start gap-2">
                  <UIcon name="i-heroicons-check-circle" class="text-green-500 mt-1 flex-shrink-0" />
                  <span>Performed live deployments and CI/CD operations, ensuring stability and smooth rollouts to production environments</span>
                </li>
              </ul>
            </div>

            <!-- Integration & Third-Party Services -->
            <div>
              <h4 class="font-semibold text-lg mb-3 flex items-center gap-2">
                <UIcon name="i-heroicons-puzzle-piece" class="text-primary" />
                Integration & Third-Party Services
              </h4>
              <ul class="space-y-2 ml-6">
                <li class="flex items-start gap-2">
                  <UIcon name="i-heroicons-check-circle" class="text-green-500 mt-1 flex-shrink-0" />
                  <span>Integrated and maintained Adyen payment solutions for secure transaction processing</span>
                </li>
                <li class="flex items-start gap-2">
                  <UIcon name="i-heroicons-check-circle" class="text-green-500 mt-1 flex-shrink-0" />
                  <span>Implemented 3D product configurators for enhanced customer experience</span>
                </li>
                <li class="flex items-start gap-2">
                  <UIcon name="i-heroicons-check-circle" class="text-green-500 mt-1 flex-shrink-0" />
                  <span>Collaborated with external service providers for shop development and feature implementation</span>
                </li>
              </ul>
            </div>

            <!-- Operations & Maintenance -->
            <div>
              <h4 class="font-semibold text-lg mb-3 flex items-center gap-2">
                <UIcon name="i-heroicons-wrench-screwdriver" class="text-primary" />
                Operations & Maintenance
              </h4>
              <ul class="space-y-2 ml-6">
                <li class="flex items-start gap-2">
                  <UIcon name="i-heroicons-check-circle" class="text-green-500 mt-1 flex-shrink-0" />
                  <span>Handled product import errors between ERP and middleware systems</span>
                </li>
                <li class="flex items-start gap-2">
                  <UIcon name="i-heroicons-check-circle" class="text-green-500 mt-1 flex-shrink-0" />
                  <span>Managed ERP-related tickets for online shop operations</span>
                </li>
                <li class="flex items-start gap-2">
                  <UIcon name="i-heroicons-check-circle" class="text-green-500 mt-1 flex-shrink-0" />
                  <span>Maintained company Nextcloud instance (updates & configurations)</span>
                </li>
                <li class="flex items-start gap-2">
                  <UIcon name="i-heroicons-check-circle" class="text-green-500 mt-1 flex-shrink-0" />
                  <span>Troubleshot server configurations and debugged production issues</span>
                </li>
                <li class="flex items-start gap-2">
                  <UIcon name="i-heroicons-check-circle" class="text-green-500 mt-1 flex-shrink-0" />
                  <span>Utilized Rundeck for job scheduling and debugging automated tasks</span>
                </li>
              </ul>
            </div>
          </div>
        </UCard>
        </div>
      </section>

      <!-- Skills Section -->
      <section id="skills" class="py-20">
        <div class="text-center mb-12">
          <h2 class="text-4xl md:text-5xl font-bold gradient-text mb-4">Technical Skills</h2>
          <p class="text-gray-600 dark:text-gray-400 text-lg">Technologies I work with daily</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          <!-- Backend -->
          <UCard class="hover-lift backdrop-blur-sm bg-gradient-to-br from-blue-50 to-purple-50 dark:from-blue-950/50 dark:to-purple-950/50 border-2 border-blue-200 dark:border-blue-800">
            <template #header>
              <div class="flex items-center gap-3">
                <div class="p-2 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg">
                  <UIcon name="i-heroicons-server" class="text-2xl text-white" />
                </div>
                <h3 class="text-xl font-bold">Backend Development</h3>
              </div>
            </template>
            
            <div class="space-y-4">
              <div class="flex items-center gap-3 p-3 bg-white/60 dark:bg-gray-800/60 rounded-lg transition-all hover:scale-105">
                <UIcon name="i-skill-icons-php-dark" class="text-3xl" />
                <span class="font-semibold">PHP (Main)</span>
              </div>
              <div class="flex items-center gap-3 p-3 bg-white/60 dark:bg-gray-800/60 rounded-lg transition-all hover:scale-105">
                <UIcon name="i-skill-icons-symfony-dark" class="text-3xl" />
                <span class="font-semibold">Symfony</span>
              </div>
              <div class="flex items-center gap-3 p-3 bg-white/60 dark:bg-gray-800/60 rounded-lg transition-all hover:scale-105">
                <UIcon name="i-logos-api-platform" class="text-3xl" />
                <span class="font-semibold">API Platform</span>
              </div>
              <div class="flex items-center gap-3 p-3 bg-white/60 dark:bg-gray-800/60 rounded-lg transition-all hover:scale-105">
                <UIcon name="i-skill-icons-cs" class="text-3xl" />
                <span class="font-semibold">C# (Basic)</span>
              </div>
            </div>
          </UCard>

          <!-- Frontend -->
          <UCard class="hover-lift backdrop-blur-sm bg-gradient-to-br from-green-50 to-emerald-50 dark:from-green-950/50 dark:to-emerald-950/50 border-2 border-green-200 dark:border-green-800">
            <template #header>
              <div class="flex items-center gap-3">
                <div class="p-2 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg shadow-lg">
                  <UIcon name="i-heroicons-computer-desktop" class="text-2xl text-white" />
                </div>
                <h3 class="text-xl font-bold">Frontend Development</h3>
              </div>
            </template>
            
            <div class="space-y-4">
              <div class="flex items-center gap-3 p-3 bg-white/60 dark:bg-gray-800/60 rounded-lg transition-all hover:scale-105">
                <UIcon name="i-skill-icons-nuxtjs-dark" class="text-3xl" />
                <span class="font-semibold">Nuxt.js</span>
              </div>
              <div class="flex items-center gap-3 p-3 bg-white/60 dark:bg-gray-800/60 rounded-lg transition-all hover:scale-105">
                <UIcon name="i-skill-icons-vuejs-dark" class="text-3xl" />
                <span class="font-semibold">Vue.js</span>
              </div>
              <div class="flex items-center gap-3 p-3 bg-white/60 dark:bg-gray-800/60 rounded-lg transition-all hover:scale-105">
                <UIcon name="i-skill-icons-html" class="text-3xl" />
                <span class="font-semibold">HTML & CSS</span>
              </div>
              <div class="flex items-center gap-3 p-3 bg-white/60 dark:bg-gray-800/60 rounded-lg transition-all hover:scale-105">
                <UIcon name="i-skill-icons-bootstrap" class="text-3xl" />
                <span class="font-semibold">Bootstrap</span>
              </div>
              <div class="flex items-center gap-3 p-3 bg-white/60 dark:bg-gray-800/60 rounded-lg transition-all hover:scale-105">
                <UIcon name="i-skill-icons-tailwindcss-dark" class="text-3xl" />
                <span class="font-semibold">Tailwind CSS</span>
              </div>
            </div>
          </UCard>

          <!-- DevOps & Tools -->
          <UCard class="hover-lift backdrop-blur-sm bg-gradient-to-br from-orange-50 to-red-50 dark:from-orange-950/50 dark:to-red-950/50 border-2 border-orange-200 dark:border-orange-800">
            <template #header>
              <div class="flex items-center gap-3">
                <div class="p-2 bg-gradient-to-br from-orange-500 to-red-600 rounded-lg shadow-lg">
                  <UIcon name="i-heroicons-cog-6-tooth" class="text-2xl text-white" />
                </div>
                <h3 class="text-xl font-bold">DevOps & Tools</h3>
              </div>
            </template>
            
            <div class="space-y-4">
              <div class="flex items-center gap-3 p-3 bg-white/60 dark:bg-gray-800/60 rounded-lg transition-all hover:scale-105">
                <UIcon name="i-skill-icons-docker" class="text-3xl" />
                <span class="font-semibold">Docker & Docker Compose</span>
              </div>
              <div class="flex items-center gap-3 p-3 bg-white/60 dark:bg-gray-800/60 rounded-lg transition-all hover:scale-105">
                <UIcon name="i-skill-icons-git" class="text-3xl" />
                <span class="font-semibold">Git</span>
              </div>
              <div class="flex items-center gap-3 p-3 bg-white/60 dark:bg-gray-800/60 rounded-lg transition-all hover:scale-105">
                <UIcon name="i-skill-icons-postgresql-dark" class="text-3xl" />
                <span class="font-semibold">PostgreSQL</span>
              </div>
              <div class="flex items-center gap-3 p-3 bg-white/60 dark:bg-gray-800/60 rounded-lg transition-all hover:scale-105">
                <UIcon name="i-logos-jira" class="text-3xl" />
                <span class="font-semibold">Jira</span>
              </div>
              <div class="flex items-center gap-3 p-3 bg-white/60 dark:bg-gray-800/60 rounded-lg transition-all hover:scale-105">
                <UIcon name="i-logos-npm-icon" class="text-3xl" />
                <span class="font-semibold">npm & Composer</span>
              </div>
              <div class="flex items-center gap-3 p-3 bg-white/60 dark:bg-gray-800/60 rounded-lg transition-all hover:scale-105">
                <UIcon name="i-heroicons-server-stack" class="text-3xl text-orange-600" />
                <span class="font-semibold">Rundeck</span>
              </div>
            </div>
          </UCard>
        </div>
      </section>

    <!-- Current Focus Section -->
    <section class="py-16">
      <UCard>
        <template #header>
          <div class="flex items-center gap-2">
            <UIcon name="i-heroicons-academic-cap" class="text-2xl text-primary" />
            <h2 class="text-3xl font-bold">Currently Learning</h2>
          </div>
        </template>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div class="space-y-2">
            <h3 class="text-lg font-semibold flex items-center gap-2">
              <UIcon name="i-heroicons-arrow-trending-up" class="text-primary" />
              Backend Focus
            </h3>
            <ul class="space-y-2 text-gray-700 dark:text-gray-300">
              <li class="flex items-start gap-2">
                <UIcon name="i-heroicons-check-circle" class="text-green-500 mt-1" />
                <span>Advanced Symfony patterns and best practices</span>
              </li>
              <li class="flex items-start gap-2">
                <UIcon name="i-heroicons-check-circle" class="text-green-500 mt-1" />
                <span>API Platform for building robust REST APIs</span>
              </li>
              <li class="flex items-start gap-2">
                <UIcon name="i-heroicons-check-circle" class="text-green-500 mt-1" />
                <span>Real-time features with Mercure</span>
              </li>
            </ul>
          </div>
          
          <div class="space-y-2">
            <h3 class="text-lg font-semibold flex items-center gap-2">
              <UIcon name="i-heroicons-arrow-trending-up" class="text-primary" />
              Frontend Focus
            </h3>
            <ul class="space-y-2 text-gray-700 dark:text-gray-300">
              <li class="flex items-start gap-2">
                <UIcon name="i-heroicons-check-circle" class="text-green-500 mt-1" />
                <span>Nuxt 4 with the Vue Composition API</span>
              </li>
              <li class="flex items-start gap-2">
                <UIcon name="i-heroicons-check-circle" class="text-green-500 mt-1" />
                <span>Modern UI/UX with Nuxt UI</span>
              </li>
              <li class="flex items-start gap-2">
                <UIcon name="i-heroicons-check-circle" class="text-green-500 mt-1" />
                <span>TypeScript for type-safe development</span>
              </li>
            </ul>
          </div>
        </div>
      </UCard>
    </section>

      <!-- Contact Section -->
      <section id="contact" class="py-20">
        <div class="max-w-4xl mx-auto">
          <UCard class="hover-lift backdrop-blur-sm bg-gradient-to-br from-purple-50 via-pink-50 to-blue-50 dark:from-purple-950/50 dark:via-pink-950/50 dark:to-blue-950/50 border-2 border-purple-200 dark:border-purple-800">
            <template #header>
              <div class="text-center">
                <div class="inline-block p-4 bg-gradient-to-br from-purple-500 to-pink-600 rounded-2xl shadow-xl mb-4">
                  <UIcon name="i-heroicons-chat-bubble-left-right" class="text-4xl text-white" />
                </div>
                <h2 class="text-4xl md:text-5xl font-bold gradient-text">Get in Touch</h2>
              </div>
            </template>
            
            <div class="text-center space-y-8">
              <p class="text-lg md:text-xl text-gray-700 dark:text-gray-300 max-w-2xl mx-auto leading-relaxed">
                I'm always interested in connecting with fellow developers, discussing new projects, 
                or exploring opportunities in software development.
              </p>
              
              <div class="flex gap-4 justify-center flex-wrap">
                <UButton 
                  icon="i-simple-icons-github" 
                  color="gray"
                  variant="solid"
                  size="xl"
                  class="shadow-lg hover:shadow-2xl transition-all hover:scale-110"
                  to="https://github.com/Alexander-Matte"
                  target="_blank"
                  external
                >
                  GitHub
                </UButton>
                <UButton 
                  icon="i-simple-icons-linkedin" 
                  color="blue"
                  variant="solid"
                  size="xl"
                  class="shadow-lg hover:shadow-2xl transition-all hover:scale-110"
                  to="https://www.linkedin.com/in/alex-matte71/"
                  target="_blank"
                  external
                >
                  LinkedIn
                </UButton>
                <UButton 
                  icon="i-heroicons-envelope" 
                  color="green"
                  variant="solid"
                  size="xl"
                  class="shadow-lg hover:shadow-2xl transition-all hover:scale-110"
                  to="mailto:alex.matte71@gmail.com"
                  external
                >
                  Email
                </UButton>
              </div>
            </div>
          </UCard>
        </div>
      </section>
    </UContainer>
  </div>
</template>
