<template>
  <div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <div class="bg-white rounded-lg shadow-md p-8">
        <div class="flex items-center justify-between mb-8">
          <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
          <button 
            @click="logout" 
            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition"
          >
            გასვლა
          </button>
        </div>
        
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
          <h2 class="text-xl font-semibold text-blue-900 mb-4">კეთილი იყოს თქვენი მობრძანება!</h2>
          <p class="text-blue-700">თქვენ წარმატებით შეხვედით თქვენს ანგარიშში.</p>
          <p class="text-blue-700 mt-2">ეს არის დაცული გვერდი, რომელიც საჭიროებს ავტორიზაციას.</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'Dashboard',
  methods: {
    async logout() {
      try {
        const token = localStorage.getItem('auth_token');
        await axios.post('/api/auth/logout', {}, {
          headers: {
            'Authorization': `Bearer ${token}`
          }
        });
        
        localStorage.removeItem('auth_token');
        this.$router.push('/auth');
      } catch (error) {
        console.error('Logout error:', error);
        localStorage.removeItem('auth_token');
        this.$router.push('/auth');
      }
    }
  }
};
</script>

