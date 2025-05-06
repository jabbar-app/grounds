<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { router } from '@inertiajs/vue3'
import Swal from 'sweetalert2'
import NavbarComponent from '@/components/NavbarComponent.vue'

const isMenuOpen = ref(false)

const { quiz, registered } = defineProps<{
  quiz: {
    id: number
    slug: string
    title: string
    description?: string
    duration: number
    open_gate_time: string | null
    close_gate_time: string | null
    announcement_time: string | null
    img_featured?: string
    category?: string
    level?: string
    is_required_monitoring?: boolean
  },
  registered: boolean
}>()

const now = ref(new Date())
let timerInterval: number

onMounted(() => {
  timerInterval = window.setInterval(() => {
    now.value = new Date()
  }, 1000)
})

onUnmounted(() => clearInterval(timerInterval))

const countdown = (target: string | null) => {
  if (!target) return ''
  const t = new Date(target).getTime()
  const diff = t - now.value.getTime()
  if (diff <= 0) return 'Selesai'

  const days = Math.floor(diff / (1000 * 60 * 60 * 24))
  const hours = Math.floor((diff / (1000 * 60 * 60)) % 24)
  const minutes = Math.floor((diff / 1000 / 60) % 60)
  const seconds = Math.floor((diff / 1000) % 60)

  return `${days > 0 ? days + 'h ' : ''}${hours}j ${minutes}m ${seconds}s`
}

const getStatusBadge = computed(() => {
  const nowTime = now.value.getTime()
  const open = quiz.open_gate_time ? new Date(quiz.open_gate_time).getTime() : null
  const close = quiz.close_gate_time ? new Date(quiz.close_gate_time).getTime() : null

  if (close && nowTime > close) {
    return { text: '⛔ Pendaftaran ditutup', class: 'bg-red-100 text-red-700' }
  }
  if (open && nowTime < open) {
    return { text: `🚀 Mulai dalam ${countdown(quiz.open_gate_time)}`, class: 'bg-green-100 text-green-700' }
  }
  return { text: `⏳ Ditutup dalam ${countdown(quiz.close_gate_time)}`, class: 'bg-yellow-100 text-yellow-800' }
})

const canStartQuiz = computed(() => {
  const nowTime = now.value.getTime()
  const open = quiz.open_gate_time ? new Date(quiz.open_gate_time).getTime() : null
  const close = quiz.close_gate_time ? new Date(quiz.close_gate_time).getTime() : null

  return (!open || nowTime >= open) && (!close || nowTime <= close)
})

const registerQuiz = async () => {
  try {
    await router.post(`/quizzes/${quiz.slug}/register`, {}, {
      onSuccess: () => {
        Swal.fire({
          icon: 'success',
          title: 'Berhasil mendaftar',
          text: 'Kamu akan mendapatkan pengingat saat quiz dimulai!',
          toast: true,
          position: 'top-end',
          timer: 2500,
          showConfirmButton: false,
        })
      },
    })
  } catch (e) {
    console.log(e);
    Swal.fire('Oops', 'Terjadi kesalahan saat mendaftar.', 'error')
  }
}

const startQuiz = () => {
  router.visit(`/quizzes/${quiz.slug}/start`)
}

const goToExplore = () => {
  router.visit('/explore')
}

const resolveImageUrl = (path?: string) => {
  return path ? `/${path}` : '/featured-images/404.jpg'
}
</script>

<template>
  <div class="min-h-screen bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100">
    <!-- Navbar -->
    <NavbarComponent :isMenuOpen="isMenuOpen" @toggle-menu="isMenuOpen = !isMenuOpen" />

    <div class="container mx-auto max-w-3xl py-12 px-6">
      <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden">
        <!-- Banner -->
        <img :src="resolveImageUrl(quiz.img_featured)" alt="Quiz Banner" class="w-full h-auto object-cover" />

        <div class="p-6 text-left">
          <!-- Status Badge -->
          <div class="mb-4">
            <span :class="`inline-block px-4 py-1 text-sm font-semibold rounded-full ${getStatusBadge.class}`">
              {{ getStatusBadge.text }}
            </span>
          </div>

          <!-- Title -->
          <h1 class="text-3xl font-bold mb-2">{{ quiz.title }}</h1>

          <!-- Kategori, Level, Monitoring -->
          <div class="flex flex-wrap items-center gap-2 text-sm mb-4">
            <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full">{{ quiz.category || 'Umum'
            }}</span>
            <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full">{{ quiz.level || 'Semua Level' }}</span>
            <span v-if="quiz.is_required_monitoring" class="bg-red-100 text-red-600 px-3 py-1 rounded-full">🎥
              Monitoring Aktif</span>
          </div>

          <!-- Deskripsi -->
          <p class="text-sm text-gray-600 dark:text-gray-300 mb-6 whitespace-pre-line">
            {{ quiz.description || 'Quiz ini terdiri dari soal-soal terbaik. Ikuti dan menangkan leaderboard!' }}
          </p>

          <!-- Info waktu -->
          <div class="text-sm text-gray-500 dark:text-gray-400 space-y-1 mb-6">
            <p><strong>Durasi:</strong> {{ Math.floor(quiz.duration / 60) }} menit</p>
            <p v-if="quiz.open_gate_time"><strong>Mulai:</strong> {{ new
              Date(quiz.open_gate_time).toLocaleString('id-ID') }}</p>
            <p v-if="quiz.close_gate_time"><strong>Tutup:</strong> {{ new
              Date(quiz.close_gate_time).toLocaleString('id-ID') }}</p>
            <p v-if="quiz.announcement_time"><strong>Pengumuman:</strong> {{ new
              Date(quiz.announcement_time).toLocaleString('id-ID') }}</p>
          </div>

          <!-- Aksi -->
          <div class="space-y-2">
            <button v-if="!registered" :disabled="getStatusBadge.text.includes('ditutup')" @click="registerQuiz"
              class="cursor-pointer w-full rounded-lg bg-indigo-600 px-5 py-3 text-sm font-semibold text-white hover:bg-indigo-700 shadow disabled:opacity-50">
              Daftar Quiz
            </button>

            <div v-else class="text-green-600 font-medium text-sm">
              ✅ Kamu sudah mendaftar quiz ini.
            </div>

            <button v-if="registered && canStartQuiz" @click="startQuiz"
              class="cursor-pointer w-full rounded-lg bg-green-600 px-5 py-3 text-sm font-semibold text-white hover:bg-green-700 shadow">
              Mulai Quiz
            </button>

            <button v-else-if="registered && !canStartQuiz" disabled
              class="w-full rounded-lg bg-gray-300 text-gray-600 px-5 py-3 text-sm font-semibold cursor-not-allowed">
              Quiz Belum Dimulai
            </button>
          </div>

          <!-- Tombol Kembali -->
        <div class="mt-6 text-center">
          <button @click="goToExplore"
            class="cursor-pointer inline-block rounded-lg bg-gray-200 dark:bg-gray-700 px-5 py-2 text-sm font-semibold text-gray-800 dark:text-white hover:bg-gray-300 dark:hover:bg-gray-600">
            ← Kembali ke Explore
          </button>
        </div>

        </div>
      </div>
    </div>
  </div>
</template>
