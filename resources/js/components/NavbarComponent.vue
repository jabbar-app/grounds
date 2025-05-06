<template>
  <nav class="px-4 py-4 bg-white dark:bg-gray-800 shadow-md sticky top-0 z-40 md:flex items-center justify-between">
    <div class="flex items-center justify-between">
      <div class="text-2xl font-bold text-indigo-700 dark:text-indigo-400">Ground</div>
      <button @click="$emit('toggle-menu')" class="md:hidden text-gray-700 dark:text-gray-200 focus:outline-none"
        aria-label="Toggle menu">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
      </button>
    </div>

    <!-- Mobile Menu -->
    <transition name="fade-slide">
      <div v-if="isMenuOpen" class="md:hidden mt-4 space-y-3 animate-fade-in">
        <a href="/" class="block text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-indigo-600">Home</a>
        <a href="https://inisiator.com/" target="_blank"
          class="block text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-indigo-600">Inisiator</a>
        <a href="#testimoni"
          class="block text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-indigo-600">Testimoni</a>
        <a href="#kontak"
          class="block text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-indigo-600">Kontak</a>
        <hr class="border-white border-dashed my-4" />

        <a v-if="!user" href="/login"
          class="block text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-indigo-600">
          Login →
        </a>
        <a v-else href="/dashboard"
          class="block text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-indigo-600">
          Dashboard
        </a>

        <!-- Theme Switcher - Mobile -->
        <div>
          <label class="block text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">Tema</label>
          <select v-model="theme" @change="setTheme(theme)"
            class="w-full px-2 py-1 rounded border dark:bg-gray-800 dark:text-white">
            <option value="light">☀️ Light</option>
            <option value="dark">🌙 Dark</option>
            <option value="system">🖥️ System</option>
          </select>
        </div>
      </div>
    </transition>

    <!-- Desktop Menu -->
    <div class="hidden md:flex items-center justify-end space-x-6 mt-4 md:mt-0">
      <a href="/"
        class="text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-indigo-600 transition">Home</a>
      <a href="/explore"
        class="text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-indigo-600 transition">Explore</a>
      <a href="https://inisiator.com/" target="_blank"
        class="text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-indigo-600 transition">Stories</a>

      <!-- Theme Switcher - Desktop -->
      <div class="relative">
        <select v-model="theme" @change="setTheme(theme)"
          class="text-sm px-2 py-1 rounded border dark:bg-gray-800 dark:text-white">
          <option value="light">☀️ Light</option>
          <option value="dark">🌙 Dark</option>
          <option value="system">🖥️ System</option>
        </select>
      </div>

      <a v-if="!user" href="/login"
        class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
        Login →
      </a>
      <a v-else href="/dashboard" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
        Dashboard
      </a>
    </div>
  </nav>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'

defineProps<{
  isMenuOpen: boolean
}>()

defineEmits<{
  (e: 'toggle-menu'): void
}>()

const user = ref<{ id: number; name: string } | null>(null)
const theme = ref(localStorage.getItem('theme') || 'light')

const setTheme = (value: string) => {
  theme.value = value
  localStorage.setItem('theme', value)

  const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches

  if (value === 'dark' || (value === 'system' && prefersDark)) {
    document.documentElement.classList.add('dark')
  } else {
    document.documentElement.classList.remove('dark')
  }
}

onMounted(() => {
  user.value = window.authUser ?? null
  setTheme(theme.value)

  // optional: sync with system change
  window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
    if (theme.value === 'system') setTheme('system')
  })
})
</script>

<style scoped>
.animate-fade-in {
  animation: fadeIn 0.3s ease-out both;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(10px);
  }

  to {
    opacity: 1;
    transform: translateY(0);
  }
}
</style>
