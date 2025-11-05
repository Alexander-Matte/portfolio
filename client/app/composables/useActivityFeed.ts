import { ref, onMounted, onUnmounted } from 'vue'
import { useRuntimeConfig } from '#imports'

export function useActivityFeed(topic: string = 'http://localhost/topics/activities') {
  const config = useRuntimeConfig()
  const updates = ref<any[]>([])
  let eventSource: EventSource | null = null

  onMounted(() => {
    const mercureUrl = config.public.mercureUrl as string
    // Use the configured topic or default
    const topicUrl = topic.startsWith('http') ? topic : `${mercureUrl.split('/.well-known/mercure')[0]}${topic}`
    const url = new URL(mercureUrl)
    url.searchParams.append('topic', topicUrl)

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
