export const useQuickActions = (
  selectedEndpoint: Ref<string>,
  selectedMethod: Ref<string>,
  formData: Ref<Record<string, any>>
) => {
  const createSampleTask = async () => {
    selectedEndpoint.value = '/api/tasks'
    selectedMethod.value = 'POST'
    await nextTick()
    formData.value = {
      title: "Hello World!",
      description: "I am a sample task",
      completed: true
    }
  }

  const createSampleNote = async () => {
    selectedEndpoint.value = '/api/notes'
    selectedMethod.value = 'POST'
    await nextTick()
    formData.value = {
      title: "Sample Note",
      content: "This is a sample note created from the API Playground!"
    }
  }

  return {
    createSampleTask,
    createSampleNote,
  }
}

