<template>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <Navbar />
    
    <main class="max-w-[1485px] mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8 mb-8">
        <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
          გამარჯობა, {{ authStore.userName }}! 👋
        </h2>
        <p class="text-gray-600 dark:text-gray-400">
          კეთილი იყოს თქვენი დაბრუნება თქვენს სამედიცინო პორტალზ
        </p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
          <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
            პირადი ინფორმაცია
          </h3>
          <div class="space-y-3" v-if="authStore.user">
            <div>
              <p class="text-sm text-gray-600 dark:text-gray-400">სახელი</p>
              <p class="text-gray-900 dark:text-white font-medium">{{ authStore.user.first_name }}</p>
            </div>
            <div>
              <p class="text-sm text-gray-600 dark:text-gray-400">გვარი</p>
              <p class="text-gray-900 dark:text-white font-medium">{{ authStore.user.last_name }}</p>
            </div>
            <div>
              <p class="text-sm text-gray-600 dark:text-gray-400">ელ. ფოსტა</p>
              <p class="text-gray-900 dark:text-white font-medium">{{ authStore.user.email }}</p>
            </div>
            <div v-if="authStore.user.phone">
              <p class="text-sm text-gray-600 dark:text-gray-400">ტელეფონი</p>
              <p class="text-gray-900 dark:text-white font-medium">{{ authStore.user.phone }}</p>
            </div>
            <div>
              <p class="text-sm text-gray-600 dark:text-gray-400">როლი</p>
              <p class="text-gray-900 dark:text-white font-medium">
                {{ authStore.user.role === 'PATIENT' ? 'პაციენტი' : authStore.user.role === 'DOCTOR' ? 'ექიმი' : 'ადმინისტრატორი' }}
              </p>
            </div>
          </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
          <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
            სწრაფი ნავიგაცია
          </h3>
          <div class="space-y-3">
            <button
              @click="$router.push('/users')"
              class="w-full flex items-center justify-start gap-3 px-4 py-3 text-left border-2 border-gray-200 dark:border-gray-700 rounded-lg hover:border-blue-500 dark:hover:border-blue-500 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-all"
            >
              <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
              </svg>
              <span class="font-medium text-gray-900 dark:text-white">მომხმარებლები</span>
            </button>
            <button
              @click="$router.push('/patients')"
              class="w-full flex items-center justify-start gap-3 px-4 py-3 text-left border-2 border-gray-200 dark:border-gray-700 rounded-lg hover:border-blue-500 dark:hover:border-blue-500 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-all"
            >
              <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
              </svg>
              <span class="font-medium text-gray-900 dark:text-white">პაციენტები</span>
            </button>
            <button
              @click="$router.push('/payments')"
              class="w-full flex items-center justify-start gap-3 px-4 py-3 text-left border-2 border-gray-200 dark:border-gray-700 rounded-lg hover:border-blue-500 dark:hover:border-blue-500 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-all"
            >
              <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
              </svg>
              <span class="font-medium text-gray-900 dark:text-white">გადახდები</span>
            </button>
          </div>
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">დაგეგმილი ვიზიტები</p>
              <p class="text-3xl font-bold text-gray-900 dark:text-white">0</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
              <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
              </svg>
            </div>
          </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">სამედიცინო ჩანაწერები</p>
              <p class="text-3xl font-bold text-gray-900 dark:text-white">0</p>
            </div>
            <div class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
              <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
              </svg>
            </div>
          </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">გავლილი ვიზიტები</p>
              <p class="text-3xl font-bold text-gray-900 dark:text-white">0</p>
            </div>
            <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center">
              <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script>
import { useAuthStore } from '../stores/auth';
import Navbar from './Navbar.vue';

export default {
  name: 'Dashboard',
  components: {
    Navbar
  },
  setup() {
    const authStore = useAuthStore();
    return { authStore };
  },
  mounted() {
    // Load user from storage on mount
    this.authStore.loadFromStorage();
  }
};
</script>
