<template>
    <div class="flex items-center justify-center min-h-screen bg-gray-50 dark:bg-gray-900 px-4">
        <div class="text-center space-y-4">
            <h2 class="text-2xl font-bold text-red-600">🚫 Leaderboard Belum Tersedia</h2>
            <p class="text-gray-700 dark:text-gray-300 text-sm max-w-md mx-auto">
                {{ message }}
            </p>

            <button @click="goBack"
                class="mt-4 inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                ← Kembali
            </button>
        </div>
    </div>
</template>

<script setup lang="ts">
import { router } from '@inertiajs/vue3'

defineProps<{
    message: string
}>()

const goBack = () => {
    router.visit('/dashboard')
}
</script>
