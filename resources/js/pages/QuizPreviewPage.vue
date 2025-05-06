<template>
    <div class="container mx-auto p-6">
        <!-- Judul Quiz -->
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-2">
            {{ quiz.title }}
        </h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
            Total Soal: {{ quiz.questions.length }}
        </p>

        <!-- Daftar Pertanyaan -->
        <div v-if="quiz.questions.length" class="space-y-6">
            <div v-for="(question, idx) in quiz.questions" :key="question.id" class="border-b pb-4">
                <h3 class="font-semibold text-gray-800 dark:text-white">
                    {{ idx + 1 }}. {{ question.question_text }}
                </h3>
                <ul class="mt-2 space-y-1 list-disc list-inside">
                    <li v-for="opt in question.options" :key="opt.id" class="text-gray-700 dark:text-gray-300">
                        {{ opt.option_text }}
                        <span v-if="opt.is_correct" class="ml-2 text-green-600 dark:text-green-400 font-semibold">
                            (Benar)
                        </span>
                    </li>
                </ul>
            </div>
        </div>

        <div v-else class="text-gray-500 dark:text-gray-400 mt-4">
            Belum ada soal ditambahkan ke quiz ini.
        </div>

        <!-- Tombol Mulai -->
        <div class="text-right mt-10">
            <button @click="startQuiz"
                class="cursor-pointer rounded-lg bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow hover:bg-indigo-700 transition">
                Lihat Quiz
            </button>
        </div>
    </div>
</template>

<script setup lang="ts">
import { router } from '@inertiajs/vue3'

const { quiz } = defineProps<{
    quiz: {
        id: number
        title: string
        slug?: string
        questions: {
            id: number
            question_text: string
            options: {
                id: number
                option_text: string
                is_correct: boolean
            }[]
        }[]
    }
}>()

const startQuiz = () => {
    router.visit(`/quizzes/${quiz.slug || quiz.id}`)
}
</script>
