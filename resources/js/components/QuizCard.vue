<template>
    <div
        class="bg-white dark:bg-gray-900 shadow-lg rounded-2xl overflow-hidden transition-transform transform hover:scale-[1.02] hover:shadow-xl duration-300 p-0 group animate-fade-slide relative">
        <!-- Countdown Badge -->
        <div
            class="absolute top-3 left-3 z-10 bg-white/90 dark:bg-gray-800/90 backdrop-blur px-4 py-2 rounded-lg text-sm font-semibold">
            <template v-if="quiz.close_gate_time && new Date() > new Date(quiz.close_gate_time)">
                <span class="text-red-600">🔒 Pendaftaran sudah ditutup</span>
            </template>
            <template v-else-if="quiz.open_gate_time && new Date() < new Date(quiz.open_gate_time)">
                <span class="text-green-600">🚀 Mulai dalam: {{ countdown?.open || '...' }}</span>
            </template>
            <template v-else-if="quiz.close_gate_time">
                <span class="text-orange-600">⏳ Ditutup dalam: {{ countdown?.close || '...' }}</span>
            </template>
        </div>

        <!-- Gambar Thumbnail -->
        <img :src="quiz.img_featured || '/featured-images/404.jpg'" alt="Quiz Banner"
            class="w-full h-40 object-cover" />

        <div class="p-6 flex flex-col h-full">
            <!-- Title -->
            <h2 class="text-xl font-bold text-gray-800 dark:text-white mb-2 group-hover:text-indigo-600 transition">
                {{ quiz.title }}
            </h2>

            <!-- Deskripsi -->
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-3 line-clamp-2">
                {{ quiz.description || 'Kuis menarik dengan tantangan soal-soal terbaik untuk kamu ikuti.' }}
            </p>

            <!-- Kategori & Level -->
            <div class="flex items-center justify-between text-xs text-gray-500 mb-3">
                <span class="bg-blue-100 text-blue-800 px-2 py-0.5 rounded">
                    {{ quiz.category || 'Umum' }}
                </span>
                <span class="bg-yellow-100 text-yellow-800 px-2 py-0.5 rounded">
                    {{ quiz.level || 'Semua Level' }}
                </span>
            </div>

            <!-- Durasi & Statistik -->
            <div class="flex justify-between text-xs text-gray-400 mb-3">
                <span>⏱ {{ Math.floor(quiz.duration / 60) || 45 }} menit</span>
                <span>👥 {{ quiz.registrations_count }} participants</span>
            </div>

            <!-- Tombol Detail -->
            <div class="mt-2">
                <a :href="`/quizzes/${quiz.slug || quiz.id}`"
                    class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-4 py-2 rounded-lg transition-all duration-300 shadow-md">
                    Lihat Detail →
                </a>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
defineProps<{
    quiz: {
        id: number
        title: string
        duration: number
        open_gate_time: string | null
        close_gate_time: string | null
        description?: string
        category?: string
        level?: string
        slug?: string
        img_featured?: string
        registrations_count?: number
    },
    countdown?: {
        open: string
        close: string
    }
}>()
</script>

<style scoped>
.animate-fade-slide {
    animation: fadeSlide 0.8s ease-out both;
}

@keyframes fadeSlide {
    from {
        opacity: 0;
        transform: translateY(24px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
