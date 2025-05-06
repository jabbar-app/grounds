<template>
  <div class="container mx-auto p-6">
    <h2 class="text-2xl font-bold mb-4">Quiz: {{ quiz.title }}</h2>

    <!-- Monitoring Preview -->
    <div v-if="quiz.is_required_monitoring" class="flex gap-4 mb-6 items-center">
      <div>
        <video ref="videoRef" class="w-40 h-32 rounded-md border border-gray-300 dark:border-gray-600" autoplay muted
          playsinline />
        <p class="text-xs text-gray-500 mt-1 text-center">Kamera aktif</p>
      </div>
      <div>
        <canvas ref="audioRef" width="160" height="32" class="rounded-md border border-gray-300 dark:border-gray-600" />
        <p class="text-xs text-gray-500 mt-1 text-center">Suara aktif</p>
      </div>
    </div>

    <!-- Navigation Panel -->
    <div class="flex flex-wrap gap-2 mb-6">
      <button v-for="(q, index) in quiz.questions" :key="index"
        class="w-8 h-8 rounded-full text-sm font-bold flex items-center justify-center border" :class="{
          'bg-indigo-600 text-white': answers[index] !== null,
          'bg-gray-300 text-gray-800': answers[index] === null,
          'ring-2 ring-red-500': flags.includes(index)
        }" @click="current = index">
        {{ index + 1 }}
      </button>
    </div>

    <!-- Timer & Flag -->
    <div class="flex justify-between items-center mb-4 text-sm text-gray-500">
      <div>
        <button @click="toggleFlag(current)" class="px-3 py-1 rounded border text-xs font-medium"
          :class="flags.includes(current) ? 'bg-red-100 text-red-600 border-red-300' : 'bg-gray-100 text-gray-700 border-gray-300'">
          <span v-if="flags.includes(current)">★ Ditandai</span>
          <span v-else>☆ Tandai</span>
        </button>
      </div>
      <div>Sisa waktu: <span class="font-bold text-red-600">{{ formatTime(timer) }}</span></div>
    </div>

    <!-- Soal -->
    <div v-if="current < quiz.questions.length" class="space-y-6">
      <div>
        <p class="font-semibold text-gray-800 dark:text-white">
          {{ current + 1 }}. {{ quiz.questions[current].question_text }}
        </p>
        <ul class="mt-4 space-y-2">
          <li v-for="(opt, i) in quiz.questions[current].options" :key="opt.id"
            class="cursor-pointer rounded-lg border px-4 py-2 hover:bg-indigo-50 dark:hover:bg-gray-700" :class="{
              'border-indigo-600 bg-indigo-100 dark:bg-indigo-700': answers[current] === i,
              'border-gray-300 dark:border-gray-600': answers[current] !== i,
            }" @click="selectAnswer(i)">
            {{ String.fromCharCode(65 + i) }}. {{ opt.option_text }}
          </li>
        </ul>
      </div>

      <div class="flex justify-between">
        <button :disabled="current === 0"
          class="rounded-lg bg-gray-400 px-5 py-2 text-sm font-semibold text-white shadow disabled:opacity-50"
          @click="prevQuestion">
          Kembali
        </button>

        <button @click="nextQuestion"
          class="rounded-lg bg-indigo-600 px-5 py-2 text-sm font-semibold text-white shadow hover:bg-indigo-700">
          {{ current + 1 === quiz.questions.length ? 'Selesai' : 'Lanjut' }}
        </button>
      </div>
    </div>

    <!-- Jika selesai -->
    <div v-else class="space-y-4 text-center">
      <h3 class="text-xl font-bold text-green-600">🎉 Quiz Selesai!</h3>

      <p v-if="quiz.announcement_time && new Date() < new Date(quiz.announcement_time)">
        Leaderboard akan dibuka pada:
        <strong class="text-blue-600">
          {{ quiz.announcement_time ? new Date(quiz.announcement_time).toLocaleString('id-ID') : '-' }}
        </strong>
      </p>

      <p class="text-sm text-gray-500">Terima kasih sudah mengikuti quiz ini 🙌</p>

      <div class="pt-2">
        <a href="/quizzes/history"
          class="inline-block rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow hover:bg-indigo-700">
          Lihat Riwayat Quiz
        </a>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue'
import axios from 'axios'

const { quiz } = defineProps<{
  quiz: {
    id: number
    title: string
    duration?: number
    announcement_time: string | null
    is_required_monitoring: boolean
    questions: {
      id: number
      question_text: string
      options: { id: number; option_text: string; is_correct: boolean }[]
    }[]
  }
}>()

const current = ref(0)
const timer = ref(quiz.duration ?? 300)
const intervalId = ref<number | null>(null)
const answers = ref<(number | null)[]>(Array(quiz.questions.length).fill(null))
const flags = ref<number[]>([])
const videoRef = ref<HTMLVideoElement | null>(null)
const audioRef = ref<HTMLCanvasElement | null>(null)
const cameraReady = ref(false)

const formatTime = (s: number) => {
  const m = Math.floor(s / 60)
  const sec = s % 60
  return `${m}:${sec.toString().padStart(2, '0')}`
}

onMounted(() => {
  if (quiz.is_required_monitoring) {
    startMedia().then(() => {
      if (cameraReady.value) initTimer()
    })
  } else {
    initTimer()
  }
})

onUnmounted(() => {
  if (intervalId.value) clearInterval(intervalId.value)
})

const initTimer = () => {
  const startedAt = localStorage.getItem(`quiz-${quiz.slug}-start`)
  const now = Math.floor(Date.now() / 1000)

  if (!startedAt) {
    localStorage.setItem(`quiz-${quiz.slug}-start`, now.toString())
    timer.value = quiz.duration ?? 300
  } else {
    const elapsed = now - parseInt(startedAt)
    timer.value = Math.max((quiz.duration ?? 300) - elapsed, 0)
  }

  intervalId.value = window.setInterval(() => {
    if (timer.value > 0) {
      timer.value--
    } else {
      finishQuiz()
    }
  }, 1000)
}

const startMedia = async () => {
  try {
    const stream = await navigator.mediaDevices.getUserMedia({ video: true, audio: true })
    if (videoRef.value) {
      videoRef.value.srcObject = stream
      videoRef.value.play()
    }
    cameraReady.value = true

    if (audioRef.value) {
      const ctx = new AudioContext()
      const analyser = ctx.createAnalyser()
      const source = ctx.createMediaStreamSource(stream)
      source.connect(analyser)

      const canvas = audioRef.value
      const c = canvas.getContext('2d')!

      analyser.fftSize = 256
      const bufferLength = analyser.frequencyBinCount
      const dataArray = new Uint8Array(bufferLength)

      const draw = () => {
        requestAnimationFrame(draw)
        analyser.getByteFrequencyData(dataArray)
        c.fillStyle = '#111'
        c.fillRect(0, 0, canvas.width, canvas.height)

        const barWidth = (canvas.width / bufferLength) * 2.5
        let x = 0
        for (let i = 0; i < bufferLength; i++) {
          const barHeight = dataArray[i] / 2
          c.fillStyle = `rgb(${barHeight + 100},50,150)`
          c.fillRect(x, canvas.height - barHeight, barWidth, barHeight)
          x += barWidth + 1
        }
      }

      draw()
    }
  } catch (e) {
    console.error('Register error:', e)
    alert('Kamera & mic wajib diaktifkan untuk mengerjakan quiz ini.')
  }
}

const selectAnswer = (i: number) => {
  answers.value[current.value] = i
}

const toggleFlag = (index: number) => {
  if (flags.value.includes(index)) {
    flags.value = flags.value.filter(i => i !== index)
  } else {
    flags.value.push(index)
  }
}

const nextQuestion = () => {
  if (current.value + 1 < quiz.questions.length) {
    current.value++
  } else {
    finishQuiz()
  }
}

const prevQuestion = () => {
  if (current.value > 0) {
    current.value--
  }
}

const finishQuiz = async () => {
  if (intervalId.value) clearInterval(intervalId.value)

  let score = 0
  quiz.questions.forEach((q, idx) => {
    const correctIdx = q.options.findIndex(o => o.is_correct)
    if (answers.value[idx] === correctIdx) score++
  })

  await axios.post(`/quizzes/${quiz.slug}/finish`, {
    score,
    total_questions: quiz.questions.length,
  })

  current.value = quiz.questions.length
  localStorage.removeItem(`quiz-${quiz.slug}-start`)
}
</script>
