<template>
    <div class="container mx-auto p-6">
        <h2 class="text-2xl font-bold mb-6 text-indigo-700 dark:text-white">
            🏆 Leaderboard - {{ quiz.title }}
        </h2>

        <div v-if="results.length > 0" class="overflow-x-auto animate-fade-in">
            <table class="min-w-full border divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-100 dark:bg-gray-800">
                    <tr>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-white">#</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-white">Nama</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-white">Skor</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-100 dark:divide-gray-800">
                    <tr v-for="(res, idx) in results" :key="res.id" :class="{
                        'bg-yellow-100 dark:bg-yellow-900': idx === 0,
                        'bg-gray-100 dark:bg-gray-800': idx === 1,
                        'bg-amber-100 dark:bg-amber-900': idx === 2,
                    }">
                        <td class="px-4 py-2">
                            <span v-if="idx === 0">🥇</span>
                            <span v-else-if="idx === 1">🥈</span>
                            <span v-else-if="idx === 2">🥉</span>
                            <span v-else>#{{ idx + 1 }}</span>
                        </td>
                        <td class="px-4 py-2 text-sm font-medium text-gray-800 dark:text-white">{{ res.user.name }}</td>
                        <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-300">
                            {{ res.score }} / {{ res.total_questions }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div v-else class="text-gray-500 dark:text-gray-400 text-sm mt-6">
            Belum ada peserta yang mengikuti quiz ini 😔
        </div>
    </div>
</template>

<script setup lang="ts">
defineProps<{
    quiz: {
        id: number
        title: string
    },
    results: {
        id: number
        score: number
        total_questions: number
        user: { name: string }
    }[]
}>()
</script>

<style scoped>
@keyframes fade-in {
    from {
        opacity: 0;
        transform: translateY(12px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fade-in 0.4s ease-out;
}
</style>
