<template>
  <div class="min-h-screen bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100">
    <!-- Navbar -->
    <NavbarComponent :isMenuOpen="isMenuOpen" @toggle-menu="isMenuOpen = !isMenuOpen" />

    <!-- Hero -->
    <section class="bg-gradient-to-br from-indigo-700 to-purple-700 text-white py-20 px-4 text-center animate-fade-in">
      <h1 class="text-4xl md:text-5xl font-bold mb-4">Kenalkan, Grounds by Inisiator.</h1>
      <p class="text-xl italic mb-2">“Saya tidak tahu kalau Grounds bisa sehebat ini.”</p>
      <p class="mb-8 text-sm">- Hampir semua pengguna baru</p>
      <h2 class="text-2xl md:text-3xl font-semibold max-w-2xl mx-auto mb-6">
        Buat dan sampaikan materi pembelajaran dari awal hingga akhir, dengan pendekatan adaptif dan
        menyenangkan.
      </h2>
      <div class="flex flex-col md:flex-row justify-center gap-4">
        <a href="/register"
          class="bg-white text-indigo-700 font-semibold px-6 py-3 rounded-md shadow hover:bg-gray-100 transition-all duration-300">
          Daftar Gratis →
        </a>
        <a href="/explore"
          class="border border-white text-white px-6 py-3 rounded-md hover:bg-white hover:text-indigo-700 transition-all duration-300">
          Jelajahi Quiz →
        </a>
      </div>
    </section>

    <!-- How it Works -->
    <section class="px-6 py-16 max-w-6xl mx-auto text-center">
      <p class="text-gray-500 dark:text-gray-400 text-sm mb-10">Dipercaya oleh guru di seluruh Indonesia & lebih
        dari 14 komunitas.</p>
      <h2 class="text-2xl font-bold mb-12 animate-fade-in">Kami bantu di setiap tahap pembelajaranmu</h2>
      <div class="grid md:grid-cols-3 gap-10 text-left">
        <div
          class="p-6 bg-gray-50 dark:bg-gray-800 rounded-lg shadow hover:shadow-lg transition transform hover:-translate-y-1">
          <div class="text-3xl mb-4">🧠</div>
          <h3 class="font-semibold mb-2">01. Personalisasi Materi</h3>
          <p>Ubah kurikulum jadi lebih menarik dengan bantuan AI kami — sesuaikan dengan kebutuhan peserta.
          </p>
        </div>
        <div
          class="p-6 bg-gray-50 dark:bg-gray-800 rounded-lg shadow hover:shadow-lg transition transform hover:-translate-y-1 delay-100">
          <div class="text-3xl mb-4">🎯</div>
          <h3 class="font-semibold mb-2">02. Pengajaran Diferensial</h3>
          <p>Setiap siswa unik. Grounds bantu kamu menyampaikan materi yang tepat sasaran.</p>
        </div>
        <div
          class="p-6 bg-gray-50 dark:bg-gray-800 rounded-lg shadow hover:shadow-lg transition transform hover:-translate-y-1 delay-200">
          <div class="text-3xl mb-4">📈</div>
          <h3 class="font-semibold mb-2">03. Lihat Hasil & Rayakan Progres</h3>
          <p>Umpan balik instan. Suasana fun. Pembelajaran terasa lebih hidup.</p>
        </div>
      </div>
    </section>

    <!-- Testimonials -->
    <section class="px-6 py-16 bg-gray-50 dark:bg-gray-800">
      <h2 class="text-2xl font-bold text-center mb-12 animate-fade-in">Apa Kata Para Guru?</h2>
      <div class="grid md:grid-cols-2 gap-6 max-w-5xl mx-auto">
        <div class="bg-white dark:bg-gray-900 p-6 rounded-lg shadow-md hover:shadow-lg transition">
          <p class="italic">“Saya gunakan Grounds untuk latihan mandiri dan juga sesi review. Murid saya
            selalu antusias.”</p>
          <p class="text-sm font-semibold text-right mt-4">— Bu Rina, Guru Matematika</p>
        </div>
        <div class="bg-white dark:bg-gray-900 p-6 rounded-lg shadow-md hover:shadow-lg transition">
          <p class="italic">“Meningkatkan keterlibatan siswa, memberi kepercayaan diri, dan mudah disesuaikan
            untuk berbagai kebutuhan belajar.”</p>
          <p class="text-sm font-semibold text-right mt-4">— Pak Irwan, Dosen Teknologi Pendidikan</p>
        </div>
      </div>
    </section>

    <!-- CTA -->
    <section class="text-center py-20 bg-indigo-700 text-white animate-fade-in">
      <h2 class="text-3xl font-bold mb-4">Mulai Ubah Cara Kamu Mengajar Hari Ini</h2>
      <p class="mb-6 text-sm">Grounds bantu kamu menciptakan pengalaman belajar yang lebih inklusif dan
        menyenangkan.</p>
      <div class="flex flex-col md:flex-row justify-center gap-4">
        <a href="#"
          class="bg-white text-indigo-700 px-6 py-3 rounded-lg font-semibold shadow hover:bg-gray-100 transition">Daftar
          Gratis</a>
        <a href="#"
          class="border border-white px-6 py-3 rounded-lg hover:bg-white hover:text-indigo-700 transition">Pelajari
          Lebih Lanjut</a>
      </div>
    </section>

    <!-- Footer -->
    <footer class="text-sm py-10 px-4 bg-white dark:bg-gray-900 text-center text-gray-600 dark:text-gray-400">
      <p>&copy; 2024 Grounds.id</p>
      <p class="mt-1">Membawa semangat belajar digital ke setiap sudut Nusantara.</p>
    </footer>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useHead } from '@vueuse/head'

useHead({
  title: 'Grounds - Platform Pembelajaran Interaktif & Aman',
  meta: [
    {
      name: 'description',
      content:
        'Grounds adalah platform pembelajaran online yang interaktif, aman, dan mudah digunakan untuk guru, siswa, dan institusi pendidikan di Indonesia.',
    },
    {
      name: 'keywords',
      content:
        'quiz, pendidikan, platform belajar, digital learning, interaktif, Indonesia, guru, siswa',
    },
    {
      property: 'og:title',
      content: 'Grounds - Platform Belajar Interaktif',
    },
  ],
  link: [
    {
      rel: 'icon',
      href: '/favicon.ico',
    },
  ],
})

import NavbarComponent from '@/components/NavbarComponent.vue'
const isMenuOpen = ref(false)
</script>

<style scoped>
@keyframes fade-in {
  from {
    opacity: 0;
    transform: translateY(10px);
  }

  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.animate-fade-in {
  animation: fade-in 0.8s ease-out both;
}
</style>
