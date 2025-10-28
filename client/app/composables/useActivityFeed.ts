import { ref, onMounted, onUnmounted } from 'vue'
import { useRuntimeConfig } from '#imports'

export function useActivityFeed(topic: string = 'http://localhost/topics/activities') {
  const config = useRuntimeConfig()
  const updates = ref<any[]>([])
  let eventSource: EventSource | null = null

  onMounted(() => {
    const mercureUrl = config.public.mercureUrl as string
    const url = new URL(mercureUrl)
    url.searchParams.append('topic', topic)

    eventSource = new EventSource(url.toString())

    eventSource.onmessage = (event) => {
      const activity = JSON.parse(event.data)
      updates.value = [activity, ...updates.value]
    }
  })

  onUnmounted(() => {
    eventSource?.close()
  })

  return { updates }
}
