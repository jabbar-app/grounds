<template>

    <Head title="Dashboard" />
    <div class="min-h-screen bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100">
        <NavbarComponent :isMenuOpen="isMenuOpen" @toggle-menu="isMenuOpen = !isMenuOpen" />

        <!-- Hero -->
        <section
            class="relative isolate overflow-hidden bg-gradient-to-br from-indigo-700 via-purple-700 to-pink-600 text-white py-25 px-6">
            <div class="max-w-4xl mx-auto text-center">
                <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight mb-4 animate-fade-in">
                    Challenge Yourself, Prove Your Skills
                </h1>
                <p class="text-lg md:text-xl text-white/90 mb-8 animate-fade-in delay-200">
                    Join exciting quizzes, boost your rank, and become a leaderboard champion!
                </p>
                <a href="#quizzes"
                    class="inline-block rounded-lg bg-white/10 hover:bg-white/20 transition px-6 py-3 font-semibold text-white shadow-lg backdrop-blur-md">
                    Explore Quizzes →
                </a>
            </div>

            <div class="absolute inset-0 opacity-30 pointer-events-none">
                <div
                    class="absolute top-0 left-0 w-72 h-72 bg-pink-500 rounded-full blur-3xl opacity-20 -translate-x-1/2 -translate-y-1/2">
                </div>
                <div
                    class="absolute bottom-0 right-0 w-72 h-72 bg-indigo-500 rounded-full blur-3xl opacity-20 translate-x-1/2 translate-y-1/2">
                </div>
            </div>
        </section>

        <section class="px-4 py-16 max-w-7xl mx-auto" id="quizzes">
            <!-- OPEN QUIZZES -->
            <div v-if="openQuizzes.length" class="mb-12">
                <h2 class="text-2xl font-bold mb-1 text-indigo-700 dark:text-indigo-400">🟢 Ongoing Quizzes</h2>
                <p class="mb-6 text-sm text-gray-600 dark:text-gray-400">These quizzes are currently open. Join now and
                    test your knowledge!</p>
                <div class="grid gap-8 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
                    <QuizCard v-for="quiz in openQuizzes" :key="quiz.id" :quiz="quiz"
                        :countdown="countdowns[quiz.id]" />
                </div>
            </div>

            <!-- UPCOMING QUIZZES -->
            <div v-if="upcomingQuizzes.length" class="mb-12">
                <h2 class="text-2xl font-bold mb-1 text-yellow-600 dark:text-yellow-400">🚀 Upcoming Quizzes</h2>
                <p class="mb-6 text-sm text-gray-600 dark:text-gray-400">Mark your calendar! These quizzes are opening
                    soon.</p>
                <div class="grid gap-8 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
                    <QuizCard v-for="quiz in upcomingQuizzes" :key="quiz.id" :quiz="quiz"
                        :countdown="countdowns[quiz.id]" />
                </div>
            </div>

            <!-- CLOSED QUIZZES -->
            <div v-if="closedQuizzes.length">
                <h2 class="text-2xl font-bold mb-1 text-gray-600 dark:text-gray-300">🔒 Closed Quizzes</h2>
                <p class="mb-6 text-sm text-gray-600 dark:text-gray-400">These quizzes are no longer accepting
                    participants.</p>
                <div class="grid gap-8 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
                    <QuizCard v-for="quiz in closedQuizzes" :key="quiz.id" :quiz="quiz"
                        :countdown="countdowns[quiz.id]" />
                </div>
            </div>

            <!-- EMPTY STATE -->
            <div v-if="!openQuizzes.length && !upcomingQuizzes.length && !closedQuizzes.length"
                class="text-center text-gray-500 dark:text-gray-400 mt-20">
                <div class="text-5xl mb-2">📭</div>
                <p class="text-lg font-medium">No quizzes available at the moment.</p>
                <p class="text-sm mt-1 text-gray-400">Please check back again later.</p>
            </div>
        </section>
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted, onUnmounted, computed } from 'vue'
import { Head } from '@inertiajs/vue3'
import NavbarComponent from '@/components/NavbarComponent.vue'
import QuizCard from '@/components/QuizCard.vue'

const isMenuOpen = ref(false)

const props = defineProps<{
    quizzes: {
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
        participants_count?: number
    }[]
}>()

const now = new Date().getTime()

const openQuizzes = computed(() => props.quizzes.filter(q => {
    const open = q.open_gate_time ? new Date(q.open_gate_time).getTime() : null
    const close = q.close_gate_time ? new Date(q.close_gate_time).getTime() : null
    return (!open || open <= now) && (!close || close > now)
}))

const upcomingQuizzes = computed(() => props.quizzes.filter(q => {
    const open = q.open_gate_time ? new Date(q.open_gate_time).getTime() : null
    return open && open > now
}))

const closedQuizzes = computed(() => props.quizzes.filter(q => {
    const close = q.close_gate_time ? new Date(q.close_gate_time).getTime() : null
    return close && close <= now
}))

const countdowns = ref<Record<number, { open: string; close: string }>>({})

const updateCountdowns = () => {
    const now = Date.now()
    const result: typeof countdowns.value = {}

    props.quizzes.forEach(q => {
        const getStr = (target: string | null) => {
            if (!target) return ''
            const diff = new Date(target).getTime() - now
            if (diff <= 0) return 'Ended'

            const seconds = Math.floor((diff / 1000) % 60)
            const minutes = Math.floor((diff / 1000 / 60) % 60)
            const hours = Math.floor((diff / (1000 * 60 * 60)) % 24)
            const days = Math.floor(diff / (1000 * 60 * 60 * 24))

            let str = ''
            if (days > 0) str += `${days}d `
            if (hours > 0 || days > 0) str += `${hours}h `
            if (minutes > 0 || hours > 0 || days > 0) str += `${minutes}m `
            str += `${seconds}s`
            return str
        }

        result[q.id] = {
            open: getStr(q.open_gate_time),
            close: getStr(q.close_gate_time),
        }
    })

    countdowns.value = result
}

let interval: number

onMounted(() => {
    updateCountdowns()
    interval = setInterval(updateCountdowns, 1000)
})

onUnmounted(() => clearInterval(interval))
</script>

<style scoped>
.animate-fade-in {
    animation: fadeIn 0.8s ease-out both;
}

.animate-fade-slide {
    animation: fadeSlide 0.8s ease-out both;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(16px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
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
