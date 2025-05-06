<template>
    <div class="space-y-6">
        <div>
            <h2 class="text-lg font-semibold text-gray-800 dark:text-white">Tambah Soal</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Masukkan pertanyaan dan jawaban yang sesuai.
            </p>
        </div>

        <form @submit.prevent="addQuestion" class="space-y-6">
            <div>
                <label for="question" class="block text-sm font-medium text-gray-700 dark:text-white">
                    Pertanyaan
                </label>
                <textarea v-model="question" id="question" rows="3"
                    class="mt-1 w-full rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                    placeholder="Contoh: Siapa presiden pertama Indonesia?" required></textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-white mb-2">
                    Pilihan Jawaban
                </label>
                <div class="space-y-3">
                    <div v-for="(opt, idx) in options" :key="idx" class="flex items-center gap-3">
                        <input v-model="options[idx].text" :placeholder="`Opsi ${idx + 1}`" type="text"
                            class="flex-1 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                            required />
                        <label class="flex items-center space-x-1 text-sm text-gray-600 dark:text-gray-300">
                            <input type="radio" v-model="correct" :value="idx"
                                class="text-indigo-600 focus:ring-indigo-500" />
                            <span>Benar</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="text-right">
                <button type="submit"
                    class="rounded-lg bg-indigo-600 px-5 py-2 text-sm font-semibold text-white shadow hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:ring-offset-2">
                    Tambah Soal
                </button>
            </div>
        </form>
    </div>
</template>

<script setup lang="ts">
import axios from 'axios'
import { ref } from 'vue'

const props = defineProps < {
    quizId: number
} > ()

const question = ref('')
const options = ref([
    { text: '' },
    { text: '' },
    { text: '' },
    { text: '' },
])
const correct = ref(0)

const addQuestion = async () => {
    console.log('quizId:', props.quizId)
    await axios.post('/questions', {
        quiz_id: props.quizId,
        question_text: question.value,
        options: options.value,
        correct: correct.value,
    })

    // Reset form
    question.value = ''
    options.value = [
        { text: '' },
        { text: '' },
        { text: '' },
        { text: '' },
    ]
    correct.value = 0
}
</script>
