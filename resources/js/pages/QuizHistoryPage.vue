<script setup lang="ts">
import { onMounted } from 'vue'
import { usePage, Head } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import Swal from 'sweetalert2'

// ✅ Ambil props
const { results } = defineProps<{
  results: {
    id: number
    score: number
    total_questions: number
    created_at: string
    quiz: {
      title: string
    }
  }[]
}>()

const page = usePage()

onMounted(() => {
  const toastMessage = page.props.toast as string
  if (toastMessage) {
    Swal.fire({
      toast: true,
      position: 'top-end',
      icon: 'info',
      title: toastMessage,
      showConfirmButton: false,
      timer: 3000,
      timerProgressBar: true,
    })
  }
})
</script>

<template>

  <Head title="Riwayat Quiz" />

  <AppLayout>
    <div class="container mx-auto p-6">
      <h2 class="text-2xl font-bold mb-6">Riwayat Quiz Saya</h2>

      <div v-if="results.length > 0" class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
          <thead class="bg-gray-50 dark:bg-gray-800">
            <tr>
              <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-white">#</th>
              <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-white">Judul Quiz</th>
              <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-white">Skor</th>
              <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-white">Tanggal</th>
            </tr>
          </thead>
          <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-100 dark:divide-gray-800">
            <tr v-for="(res, idx) in results" :key="res.id">
              <td class="px-4 py-2 text-sm">{{ idx + 1 }}</td>
              <td class="px-4 py-2 text-sm">{{ res.quiz.title }}</td>
              <td class="px-4 py-2 text-sm">{{ res.score }} / {{ res.total_questions }}</td>
              <td class="px-4 py-2 text-sm">{{ new Date(res.created_at).toLocaleString('id-ID') }}</td>
            </tr>
          </tbody>
        </table>
      </div>

      <div v-else class="text-gray-500 dark:text-gray-400 text-sm">
        Belum ada hasil quiz. Ayo mulai kerjakan quiz! 💪
      </div>
    </div>
  </AppLayout>
</template>
