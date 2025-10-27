interface UserStatsResponse {
  '@context': string
  '@id': string
  '@type': string
  requestMade: number
  successfulRequests: number
  totalResponseTime: number
  tasksCreated: number
  tasksCompleted: number
  notesCreated: number
  rank: string
  badges: unknown[]
  lastActivity: string
}

export const useUserStats = () => {
  const { buildApiUrl } = useApi()
  const sessionStore = useSessionStore()

  const { data: stats, pending, refresh } = useFetch<UserStatsResponse>(
    buildApiUrl('/api/stats/me'),
    {
      headers: computed(() => ({
        'Accept': 'application/ld+json',
        'Authorization': `Bearer ${sessionStore.token}`
      })),
      lazy: true,
    }
  )


  // Calculate average response time from totalResponseTime / requestMade
  const avgResponseTime = computed(() => {
    if (!stats.value?.requestMade || stats.value.requestMade === 0) {
      return 0
    }
    return Math.round(stats.value.totalResponseTime / stats.value.requestMade)
  })


  const requestCount = computed(() => stats.value?.requestMade ?? 0)
  const tasksCreated = computed(() => stats.value?.tasksCreated ?? 0)
  const tasksCompleted = computed(() => stats.value?.tasksCompleted ?? 0)
  const notesCreated = computed(() => stats.value?.notesCreated ?? 0)
  const rank = computed(() => stats.value?.rank ?? 'Beginner')
  const badges = computed(() => stats.value?.badges ?? [])

  return {
    stats,
    requestCount,
    avgResponseTime,
    tasksCreated,
    tasksCompleted,
    notesCreated,
    rank,
    badges,
    pending,
    refresh,
  }
}

