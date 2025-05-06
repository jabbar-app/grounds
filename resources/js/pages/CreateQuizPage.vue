<template>
    <div class="container mx-auto px-4 py-6">
        <h2 class="text-2xl font-bold mb-6">Buat Quiz Baru</h2>

        <!-- Quiz Form -->
        <quiz-form @saved="handleSaved" :user-id="userId" v-if="!quizId" />

        <!-- Setelah Quiz dibuat -->
        <div v-if="quizId">
            <!-- Quiz Summary -->
            <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-4 mb-6 border dark:border-gray-600">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Quiz: {{ quizTitle || '...' }}
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">ID: #{{ quizId }}</p>
                    </div>
                    <button @click="goToPreview"
                        class="bg-green-600 hover:bg-green-700 text-white text-sm font-semibold px-4 py-2 rounded-lg shadow">
                        ✅ Selesai & Lihat Preview
                    </button>
                </div>
            </div>

            <!-- Tabbed Interface -->
            <div class="mb-4 flex border-b dark:border-gray-700">
                <button @click="activeTab = 'form'" :class="tabClass('form')">+ Tambah Pertanyaan</button>
                <button @click="activeTab = 'list'" :class="tabClass('list')">📚 Daftar Pertanyaan</button>
            </div>

            <!-- Tab Content -->
            <div>
                <question-form v-if="activeTab === 'form'" :quiz-id="quizId" @added="refreshList" />

                <div v-if="activeTab === 'list'" class="mt-4 space-y-4">
                    <div class="flex justify-between items-center mb-2">
                        <h4 class="font-semibold text-gray-700 dark:text-white">Total Soal: {{ questions.length }}</h4>
                        <button @click="refreshList"
                            class="text-sm bg-indigo-100 hover:bg-indigo-200 dark:bg-indigo-800 dark:hover:bg-indigo-700 text-indigo-700 dark:text-white font-medium px-3 py-1 rounded-md">
                            🔄 Refresh
                        </button>
                    </div>
                    <div v-if="questions.length === 0" class="text-sm text-gray-500 dark:text-gray-300 italic">
                        Belum ada pertanyaan ditambahkan.
                    </div>
                    <ul v-else class="space-y-3">
                        <li v-for="(q, idx) in questions" :key="q.id"
                            class="border dark:border-gray-700 rounded-lg p-3 bg-white dark:bg-gray-900">
                            <span class="font-medium">#{{ idx + 1 }}</span>. {{ q.question_text }}
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import Swal from 'sweetalert2'
import QuizForm from '@/components/QuizForm.vue'
import QuestionForm from '@/components/QuestionForm.vue'
import axios from 'axios'

const { userId } = defineProps<{ userId: number }>()
const quizId = ref<number | null>(null)
const quizTitle = ref<string>('')
const activeTab = ref<'form' | 'list'>('form')
const questions = ref<{ id: number; question_text: string }[]>([])

const handleSaved = async (id: number) => {
    quizId.value = id
    activeTab.value = 'form'

    // Ambil data quiz singkat
    const { data } = await axios.get(`/quizzes/get/${id}`)
    quizTitle.value = data.title || ''
    await refreshList()

    // Scroll dan SweetAlert
    window.scrollTo({ top: 0, behavior: 'smooth' })
    Swal.fire({
        icon: 'success',
        title: 'Quiz berhasil dibuat!',
        text: 'Sekarang kamu bisa menambahkan pertanyaan.',
        toast: true,
        position: 'top-end',
        timer: 2500,
        showConfirmButton: false,
    })
}

const refreshList = async () => {
    if (!quizId.value) return
    const { data } = await axios.get(`/quizzes/get/${quizId.value}/questions`)
    questions.value = data
}

const goToPreview = () => {
    if (quizId.value) {
        router.visit(`/quizzes/${quizId.value}/preview`)
    }
}

const tabClass = (tab: 'form' | 'list') => [
    'px-4 py-2 text-sm font-medium rounded-t-lg focus:outline-none',
    activeTab.value === tab
        ? 'bg-indigo-600 text-white'
        : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-600'
]
</script>
