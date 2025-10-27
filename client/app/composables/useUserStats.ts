interface UserStatsResponse {
  requestMade: number
  successfulRequests: number
  totalResponseTime: number
  averageResponseTime: number
  successRate: number
  tasksCreated: number
  tasksCompleted: number
  notesCreated: number
}

export const useUserStats = () => {
  const { buildApiUrl } = useApi()
  const sessionStore = useSessionStore()

  const { data: stats, refresh, pending } = useFetch<UserStatsResponse>(
    buildApiUrl('/api/stats/me'),
    {
      headers: computed(() => ({
        'Accept': 'application/ld+json',
        'Authorization': `Bearer ${sessionStore.token}`
      })),
      lazy: true,
    }
  )

  const requestCount = computed(() => stats.value?.requestMade ?? 0)
  const avgResponseTime = computed(() => stats.value?.averageResponseTime ?? 0)
  const successRate = computed(() => Math.round(stats.value?.successRate ?? 0))
  const tasksCreated = computed(() => stats.value?.tasksCreated ?? 0)
  const tasksCompleted = computed(() => stats.value?.tasksCompleted ?? 0)
  const notesCreated = computed(() => stats.value?.notesCreated ?? 0)

  return {
    stats,
    requestCount,
    avgResponseTime,
    successRate,
    tasksCreated,
    tasksCompleted,
    notesCreated,
    refresh,
    pending,
  }
}

