<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Head, usePage, router } from '@inertiajs/vue3'
import Swal from 'sweetalert2'
import { onMounted, ref, onUnmounted, computed } from 'vue'

import type { PageProps } from '@/types/inertia'

const page = usePage<PageProps>()
const user = page.props.auth.user!

defineProps<{
  results: {
    id: number
    score: number
    total_questions: number
    created_at: string
    quiz: {
      id: number
      title: string
      announcement_time: string | null
    }
  }[]
  registered_quizzes?: {
    id: number
    title: string
    open_gate_time: string | null
    close_gate_time: string | null
  }[]
}>()

onMounted(() => {
  const toast = page.props.toast as string
  if (toast) {
    Swal.fire({
      toast: true,
      icon: 'success',
      title: toast,
      position: 'top-end',
      showConfirmButton: false,
      timer: 2500,
      timerProgressBar: true,
    })
  }
})

const goToExplore = () => {
  router.visit('/explore')
}

const now = ref(new Date())
let timer: any

onMounted(() => {
  timer = setInterval(() => {
    now.value = new Date()
  }, 1000)
})
onUnmounted(() => clearInterval(timer))

const formatCountdown = (target: Date, prefix: string, afterText: string) => {
  const diff = target.getTime() - now.value.getTime()
  if (diff <= 0) return afterText

  const h = Math.floor(diff / 1000 / 60 / 60)
  const m = Math.floor((diff / 1000 / 60) % 60)
  const s = Math.floor((diff / 1000) % 60)
  return `${prefix} ${String(h).padStart(2, '0')}:${String(m).padStart(2, '0')}:${String(s).padStart(2, '0')}`
}

const countdownText = (target: string | null, prefix: string, fallback: string) => {
  return computed(() => {
    if (!target) return fallback
    const d = new Date(target)
    return formatCountdown(d, prefix, fallback)
  })
}
</script>

<template>

  <Head title="Dashboard" />

  <AppLayout>
    <div class="container mx-auto p-6 space-y-8">
      <!-- Hero -->
      <div class="rounded-xl bg-gradient-to-br from-indigo-600 to-purple-700 p-6 text-white shadow-md">
        <h1 class="text-2xl font-bold mb-1">Hi, {{ user.name }}</h1>
        <p class="text-sm">Selamat datang di dashboard kamu! Yuk lihat quiz yang bisa kamu ikuti hari ini!</p>
        <button @click="goToExplore"
          class="mt-4 rounded-md bg-white/10 px-4 py-2 text-sm font-medium hover:bg-white/20 transition">
          🔍 Explore Quiz
        </button>
      </div>

      <!-- Registered Quizzes -->
      <div>
        <h2 class="text-lg font-semibold mb-3">Quiz yang Sudah Kamu Daftari</h2>
        <div v-if="registered_quizzes && registered_quizzes.length > 0"
          class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
          <div v-for="quiz in registered_quizzes" :key="quiz.id"
            class="border dark:border-gray-700 rounded-lg p-4 bg-white dark:bg-gray-800">
            <h3 class="font-semibold text-indigo-700 dark:text-indigo-300 mb-1">{{ quiz.title }}</h3>
            <p class="text-sm text-gray-600 dark:text-gray-300 mb-1">
              <strong>Open:</strong>
              <span v-if="quiz.open_gate_time">
                {{ countdownText(quiz.open_gate_time, 'Mulai dalam', '✅ Sudah dibuka').value }}
              </span>
              <span v-else> Kapan saja</span>
            </p>
            <p class="text-sm text-gray-600 dark:text-gray-300 mb-2">
              <strong>Close:</strong>
              <span v-if="quiz.close_gate_time">
                {{ countdownText(quiz.close_gate_time, 'Tutup dalam', '⛔ Sudah ditutup').value }}
              </span>
              <span v-else> Tidak dibatasi</span>
            </p>
            <a :href="`/quizzes/${quiz.slug}`" class="text-indigo-600 text-sm hover:underline">Lihat
              Detail</a>
          </div>
        </div>
        <div v-else class="text-sm text-gray-500 dark:text-gray-400 mt-1">
          Belum ada quiz yang kamu daftarkan.
        </div>
      </div>

      <!-- History -->
      <div>
        <h2 class="text-lg font-semibold mb-3">Riwayat Quiz Kamu</h2>
        <div v-if="results.length > 0" class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-800">
              <tr>
                <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-white">#
                </th>
                <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-white">Quiz
                </th>
                <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-white">Skor
                </th>
                <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-white">
                  Waktu</th>
                <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-white">Aksi
                </th>
              </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-100 dark:divide-gray-800">
              <tr v-for="(res, idx) in results" :key="res.id">
                <td class="px-4 py-2 text-sm">{{ idx + 1 }}</td>
                <td class="px-4 py-2 text-sm">{{ res.quiz.title }}</td>
                <td class="px-4 py-2 text-sm">{{ res.score }} / {{ res.total_questions }}</td>
                <td class="px-4 py-2 text-sm">{{ new Date(res.created_at).toLocaleString('id-ID') }}
                </td>
                <td class="px-4 py-2 text-sm">
                  <a :href="`/quizzes/${res.quiz.id}/leaderboard`"
                    class="text-indigo-600 hover:underline">Leaderboard</a>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <div v-else class="text-sm text-gray-500 dark:text-gray-400 mt-3">
          Kamu belum pernah mengikuti quiz manapun.
        </div>
      </div>
    </div>
  </AppLayout>
</template>
