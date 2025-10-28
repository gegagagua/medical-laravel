<template>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <Navbar />
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">Dashboard</h1>
        
        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-6">
          <h2 class="text-xl font-semibold text-blue-900 dark:text-blue-100 mb-4">კეთილი იყოს თქვენი მობრძანება!</h2>
          <p class="text-blue-700 dark:text-blue-300">თქვენ წარმატებით შეხვედით თქვენს ანგარიშში.</p>
          <p class="text-blue-700 dark:text-blue-300 mt-2">ეს არის დაცული გვერდი, რომელიც საჭიროებს ავტორიზაციას.</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Navbar from './Navbar.vue';

export default {
  name: 'Dashboard',
  components: {
    Navbar
  }
};
</script>

