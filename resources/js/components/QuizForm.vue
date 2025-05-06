<template>
    <form @submit.prevent="submitQuiz" class="space-y-5">
        <!-- Judul -->
        <div>
            <label for="title" class="block text-sm font-medium text-gray-700 dark:text-white">Judul Quiz</label>
            <input v-model="title" type="text" id="title" placeholder="Contoh: Quiz Sejarah Indonesia"
                class="mt-1 w-full rounded-lg border bg-white dark:bg-gray-800 px-4 py-2 text-sm dark:text-white shadow-sm"
                required />
        </div>

        <!-- Slug -->
        <div>
            <label for="slug" class="block text-sm font-medium text-gray-700 dark:text-white">Slug (Opsional)</label>
            <input v-model="slug" type="text" id="slug" placeholder="Contoh: quiz-sejarah-indonesia"
                class="mt-1 w-full rounded-lg border bg-white dark:bg-gray-800 px-4 py-2 text-sm dark:text-white shadow-sm" />
        </div>

        <!-- Deskripsi -->
        <div>
            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-white">Deskripsi</label>
            <textarea v-model="description" id="description" rows="3"
                class="mt-1 w-full rounded-lg border bg-white dark:bg-gray-800 px-4 py-2 text-sm dark:text-white shadow-sm"
                placeholder="Deskripsikan quiz ini..."></textarea>
        </div>

        <!-- Upload Gambar Featured -->
        <div>
            <label for="img_featured" class="block text-sm font-medium text-gray-700 dark:text-white">Gambar
                Featured</label>
            <input type="file" id="img_featured" accept="image/*" @change="handleImageUpload"
                class="mt-1 block w-full text-sm text-gray-700 dark:text-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
            <img v-if="imgPreview" :src="imgPreview" alt="Preview" class="mt-3 h-32 object-cover rounded-md border" />
        </div>

        <!-- Kategori & Level -->
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="category" class="block text-sm font-medium text-gray-700 dark:text-white">Kategori</label>
                <input v-model="category" type="text" id="category" placeholder="Contoh: Sejarah"
                    class="mt-1 w-full rounded-lg border bg-white dark:bg-gray-800 px-4 py-2 text-sm dark:text-white shadow-sm" />
            </div>
            <div>
                <label for="level" class="block text-sm font-medium text-gray-700 dark:text-white">Tingkat
                    Kesulitan</label>
                <input v-model="level" type="text" id="level" placeholder="Contoh: Menengah"
                    class="mt-1 w-full rounded-lg border bg-white dark:bg-gray-800 px-4 py-2 text-sm dark:text-white shadow-sm" />
            </div>
        </div>

        <!-- Durasi -->
        <div>
            <label for="duration" class="block text-sm font-medium text-gray-700 dark:text-white">Durasi (menit)</label>
            <input v-model.number="duration" type="number" id="duration" min="1" placeholder="Contoh: 45"
                class="mt-1 w-full rounded-lg border bg-white dark:bg-gray-800 px-4 py-2 text-sm dark:text-white shadow-sm"
                required />
        </div>

        <!-- Waktu -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="open_gate_time" class="block text-sm font-medium text-gray-700 dark:text-white">Waktu
                    Mulai</label>
                <input v-model="openGateTime" type="datetime-local" id="open_gate_time"
                    class="mt-1 w-full rounded-lg border bg-white dark:bg-gray-800 px-4 py-2 text-sm dark:text-white shadow-sm" />
            </div>
            <div>
                <label for="close_gate_time" class="block text-sm font-medium text-gray-700 dark:text-white">Waktu
                    Tutup</label>
                <input v-model="closeGateTime" type="datetime-local" id="close_gate_time"
                    class="mt-1 w-full rounded-lg border bg-white dark:bg-gray-800 px-4 py-2 text-sm dark:text-white shadow-sm" />
            </div>
            <div class="md:col-span-2">
                <label for="announcement_time" class="block text-sm font-medium text-gray-700 dark:text-white">Waktu
                    Pengumuman</label>
                <input v-model="announcementTime" type="datetime-local" id="announcement_time"
                    class="mt-1 w-full rounded-lg border bg-white dark:bg-gray-800 px-4 py-2 text-sm dark:text-white shadow-sm" />
            </div>
        </div>

        <!-- Monitoring -->
        <div class="flex items-center">
            <input v-model="isMonitoring" type="checkbox" id="is_required_monitoring"
                class="mr-2 h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" />
            <label for="is_required_monitoring" class="text-sm text-gray-700 dark:text-white">Monitoring wajib
                diaktifkan
                (kamera & mic)</label>
        </div>

        <!-- Submit -->
        <div class="text-right">
            <button type="submit"
                class="rounded-lg bg-indigo-600 px-5 py-2 text-sm font-semibold text-white shadow hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:ring-offset-2">
                Simpan Quiz
            </button>
        </div>
    </form>
</template>

<script setup lang="ts">
import axios from 'axios'
import { ref } from 'vue'

const emit = defineEmits<{ (e: 'saved', id: number): void }>()

const title = ref('')
const slug = ref('')
const description = ref('')
const category = ref('')
const level = ref('')
const duration = ref(45)
const openGateTime = ref('')
const closeGateTime = ref('')
const announcementTime = ref('')
const isMonitoring = ref(false)

const imgFile = ref<File | null>(null)
const imgPreview = ref<string | null>(null)
const imgBase64 = ref<string | null>(null)

const handleImageUpload = (e: Event) => {
    const file = (e.target as HTMLInputElement).files?.[0]
    if (!file) return

    imgFile.value = file
    const reader = new FileReader()
    reader.onload = () => {
        imgBase64.value = reader.result as string
        imgPreview.value = reader.result as string
    }
    reader.readAsDataURL(file)
}

const submitQuiz = async () => {
    const res = await axios.post('/quizzes', {
        title: title.value,
        slug: slug.value || undefined,
        description: description.value,
        category: category.value,
        level: level.value,
        duration: duration.value * 60,
        open_gate_time: openGateTime.value || null,
        close_gate_time: closeGateTime.value || null,
        announcement_time: announcementTime.value || null,
        is_required_monitoring: isMonitoring.value,
        compressed_image: imgBase64.value || null,
    })

    emit('saved', res.data.id)
}
</script>
