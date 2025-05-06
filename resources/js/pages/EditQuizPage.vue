<script setup lang="ts">
import { onMounted } from 'vue'
import { useForm, Head, usePage } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import Swal from 'sweetalert2'

const { quiz } = defineProps<{
    quiz: {
        id: number
        title: string
        slug?: string
        description?: string
        img_featured?: string
        duration: number
        open_gate_time: string | null
        close_gate_time: string | null
        announcement_time: string | null
        category?: string
        level?: string
        is_required_monitoring?: boolean
    }
}>()

const form = useForm({
    title: quiz.title,
    slug: quiz.slug || '',
    description: quiz.description || '',
    img_featured: quiz.img_featured || '',
    category: quiz.category || '',
    level: quiz.level || '',
    duration: quiz.duration,
    open_gate_time: quiz.open_gate_time,
    close_gate_time: quiz.close_gate_time,
    announcement_time: quiz.announcement_time,
    is_required_monitoring: quiz.is_required_monitoring ?? false,
})

const submit = () => {
    form.put(`/quizzes/${quiz.slug}`)
}

onMounted(() => {
    const page = usePage()
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
</script>

<template>

    <Head title="Edit Quiz" />
    <AppLayout>
        <div class="container mx-auto max-w-2xl p-6">
            <h2 class="text-2xl font-bold mb-4">Edit Quiz</h2>

            <form @submit.prevent="submit" class="space-y-5">
                <!-- Title -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-white">Judul</label>
                    <input v-model="form.title" type="text"
                        class="w-full mt-1 rounded-lg border px-4 py-2 text-sm dark:bg-gray-800 dark:border-gray-600 dark:text-white" />
                    <p v-if="form.errors.title" class="text-sm text-red-600 mt-1">{{ form.errors.title }}</p>
                </div>

                <!-- Slug -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-white">Slug</label>
                    <input v-model="form.slug" type="text"
                        class="w-full mt-1 rounded-lg border px-4 py-2 text-sm dark:bg-gray-800 dark:border-gray-600 dark:text-white" />
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-white">Deskripsi</label>
                    <textarea v-model="form.description" rows="3"
                        class="w-full mt-1 rounded-lg border px-4 py-2 text-sm dark:bg-gray-800 dark:border-gray-600 dark:text-white"></textarea>
                </div>

                <!-- Image -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-white">Gambar Featured</label>
                    <input v-model="form.img_featured" type="text"
                        class="w-full mt-1 rounded-lg border px-4 py-2 text-sm dark:bg-gray-800 dark:border-gray-600 dark:text-white" />
                </div>

                <!-- Category & Level -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-white">Kategori</label>
                        <input v-model="form.category" type="text"
                            class="w-full mt-1 rounded-lg border px-4 py-2 text-sm dark:bg-gray-800 dark:border-gray-600 dark:text-white" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-white">Level</label>
                        <input v-model="form.level" type="text"
                            class="w-full mt-1 rounded-lg border px-4 py-2 text-sm dark:bg-gray-800 dark:border-gray-600 dark:text-white" />
                    </div>
                </div>

                <!-- Durasi -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-white">Durasi (detik)</label>
                    <input v-model="form.duration" type="number" min="60"
                        class="w-full mt-1 rounded-lg border px-4 py-2 text-sm dark:bg-gray-800 dark:border-gray-600 dark:text-white" />
                    <p v-if="form.errors.duration" class="text-sm text-red-600 mt-1">{{ form.errors.duration }}</p>
                </div>

                <!-- Time Settings -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-white">Waktu Mulai</label>
                        <input v-model="form.open_gate_time" type="datetime-local"
                            class="w-full mt-1 rounded-lg border px-4 py-2 text-sm dark:bg-gray-800 dark:border-gray-600 dark:text-white" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-white">Waktu Tutup</label>
                        <input v-model="form.close_gate_time" type="datetime-local"
                            class="w-full mt-1 rounded-lg border px-4 py-2 text-sm dark:bg-gray-800 dark:border-gray-600 dark:text-white" />
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-white">Waktu Pengumuman</label>
                    <input v-model="form.announcement_time" type="datetime-local"
                        class="w-full mt-1 rounded-lg border px-4 py-2 text-sm dark:bg-gray-800 dark:border-gray-600 dark:text-white" />
                </div>

                <!-- Monitoring -->
                <div class="flex items-center mt-2">
                    <input v-model="form.is_required_monitoring" type="checkbox" id="is_required_monitoring"
                        class="mr-2 h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" />
                    <label for="is_required_monitoring" class="text-sm text-gray-700 dark:text-white">
                        Monitoring aktif saat quiz (kamera & mic)
                    </label>
                </div>

                <!-- Submit -->
                <div class="text-right mt-6">
                    <button type="submit" :disabled="form.processing"
                        class="cursor-pointer rounded-lg bg-indigo-600 px-5 py-2 text-sm font-semibold text-white hover:bg-indigo-700 shadow disabled:opacity-50">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
