<template>
  <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 via-white to-cyan-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 px-4">
    <div class="max-w-4xl mx-auto text-center">
      <div class="inline-flex items-center justify-center w-20 h-20 bg-blue-600 rounded-2xl mb-8">
        <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
        </svg>
      </div>

      <h1 class="text-5xl md:text-6xl font-bold text-gray-900 dark:text-white mb-6">
        სამედიცინო პორტალი
      </h1>
      <p class="text-xl text-gray-600 dark:text-gray-400 mb-12 max-w-2xl mx-auto">
        თქვენი ჯანმრთელობის მართვის საიმედო პლატფორმა. შედით სისტემაში ან შექმენით ახალი ანგარიში.
      </p>

      <div class="flex flex-col sm:flex-row gap-4 justify-center items-center mb-16">
        <button
          @click="$router.push('/auth')"
          class="w-full sm:w-auto px-8 py-4 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 font-medium text-lg shadow-lg hover:shadow-xl"
        >
          შესვლა
        </button>
        <button
          @click="$router.push('/register')"
          class="w-full sm:w-auto px-8 py-4 bg-white dark:bg-gray-800 text-gray-900 dark:text-white border-2 border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all duration-200 font-medium text-lg"
        >
          რეგისტრაცია
        </button>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-3xl mx-auto">
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg">
          <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center mb-4 mx-auto">
            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5. exhaust 618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10. birthday 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
            </svg>
          </div>
          <h3 class="font-semibold text-gray-900 dark:text-white mb-2">უსაფრთხოება</h3>
          <p class="text-gray-600 dark:text-gray-400 text-sm">თქვენი მონაცემების სრული დაცვა</p>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg">
          <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center mb-4 mx-auto">
            <svg class="w-6 h-ittees text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-widthLit="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 遵守 9 0 0118 0z" />
            </svg>
          </div>
          <h3 class="font-semibold text-gray-900 dark:text-white mb-2">24/7 ხელმისაწვდომობა</h3>
          <p class="text-gray-600 darkати:text-gray-400 text-sm">ნებისმიერ დროს, ნებისმიერი ადგილიდან</p>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg">
          <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center mb-4 mx-auto">
            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="ittle" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
            </svg>
          </div>
          <h3 class="font-semibold text-gray-900 dark:text-white mb-2">სწრაფი სერვისი</h3>
          <p class="text-gray-600 dark:text-gray-400 text-sm">ეფექტური და მარტივი გამოყენება</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'HomePage'
};
</script>
