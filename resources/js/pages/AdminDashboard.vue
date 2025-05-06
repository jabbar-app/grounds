<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { type BreadcrumbItem } from '@/types'
import { Head, usePage } from '@inertiajs/vue3'
import Swal from 'sweetalert2'
import { watch } from 'vue'

// ✅ Props
const { quizzes } = defineProps<{
  quizzes: {
    id: number
    title: string
    duration: number
    announcement_time: string | null
    questions_count: number
  }[]
}>()

// ✅ Breadcrumbs
const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Dashboard',
    href: '/dashboard',
  },
]

// ✅ Toast watch
const page = usePage()
watch(
  () => page.props.toast,
  (toast) => {
    console.log('🔥 DASHBOARD TOAST:', toast)
    if (toast) {
      Swal.fire({
        toast: true,
        icon: 'success',
        title: toast as string,
        position: 'top-end',
        showConfirmButton: false,
        timer: 2500,
        timerProgressBar: true,
      })
    }
  },
  { immediate: true }
)
</script>

<template>

  <Head title="Dashboard" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
      <div class="grid auto-rows-min gap-4 md:grid-cols-3">
        <div
          class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
          <!-- Bisa diganti grafik ringkasan -->
        </div>
        <div
          class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
          <!-- Bisa diganti statistik -->
        </div>
        <div
          class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
          <!-- Bisa diganti informasi -->
        </div>
      </div>

      <div
        class="relative rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min bg-white dark:bg-gray-900 p-6">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Daftar Quiz yang Kamu Buat</h2>
        <div v-if="quizzes.length > 0" class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-800">
              <tr>
                <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-white">
                  Judul</th>
                <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-white">
                  Jumlah Soal</th>
                <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-white">
                  Durasi</th>
                <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-white">
                  Pengumuman</th>
                <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-white">Aksi
                </th>
              </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-100 dark:divide-gray-800">
              <tr v-for="quiz in quizzes" :key="quiz.id">
                <td class="px-4 py-2 text-sm">{{ quiz.title }}</td>
                <td class="px-4 py-2 text-sm">{{ quiz.questions_count }}</td>
                <td class="px-4 py-2 text-sm">{{ Math.floor(quiz.duration / 60) }} menit</td>
                <td class="px-4 py-2 text-sm">
                  {{ quiz.announcement_time ? new Date(quiz.announcement_time).toLocaleString('id-ID')
                    : '-' }}
                </td>
                <td class="px-4 py-2 text-sm space-x-2">
                  <a :href="`/quizzes/${quiz.slug}/preview`" class="text-blue-600 hover:underline">Preview</a>
                  <a :href="`/quizzes/${quiz.slug}/leaderboard`" class="text-green-600 hover:underline">Leaderboard</a>
                  <a :href="`/quizzes/${quiz.slug}/edit`" class="text-yellow-600 hover:underline">Edit</a>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <div v-else class="text-sm text-gray-500 dark:text-gray-400">
          Kamu belum membuat quiz apapun.
        </div>
      </div>
    </div>
  </AppLayout>
</template>
