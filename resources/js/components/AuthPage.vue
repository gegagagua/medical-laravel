<template>
  <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 flex items-center justify-center p-4">
    <div class="max-w-md w-full space-y-8 bg-white rounded-2xl shadow-xl p-8">
      <!-- Header -->
      <div class="text-center">
        <h2 class="text-3xl font-bold text-gray-900">
          {{ isLogin ? 'Welcome Back' : 'Create Account' }}
        </h2>
        <p class="mt-2 text-sm text-gray-600">
          {{ isLogin ? 'Sign in to your account' : 'Sign up to get started' }}
        </p>
      </div>

      <!-- Error Alert -->
      <div v-if="error" class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
        {{ error }}
      </div>

      <!-- Success Alert -->
      <div v-if="success" class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">
        {{ success }}
      </div>

      <!-- Loading State -->
      <div v-if="loading" class="flex items-center justify-center py-4">
        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>
      </div>

      <!-- Form -->
      <form @submit.prevent="handleSubmit" class="space-y-6">
        <!-- Register Name Field -->
        <div v-if="!isLogin">
          <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
            Full Name
          </label>
          <input
            id="name"
            v-model="form.name"
            type="text"
            required
            class="appearance-none relative block w-full px-4 py-3 border border-gray-300 rounded-lg placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm transition"
            placeholder="Enter your name"
          />
        </div>

        <!-- Email Field -->
        <div>
          <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
            Email Address
          </label>
          <input
            id="email"
            v-model="form.email"
            type="email"
            required
            class="appearance-none relative block w-full px-4 py-3 border border-gray-300 rounded-lg placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm transition"
            placeholder="Enter your email"
          />
        </div>

        <!-- Password Field -->
        <div>
          <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
            Password
          </label>
          <input
            id="password"
            v-model="form.password"
            type="password"
            required
            :minlength="isLogin ? 1 : 8"
            class="appearance-none relative block w-full px-4 py-3 border border-gray-300 rounded-lg placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm transition"
            :placeholder="isLogin ? 'Enter your password' : 'Enter your password (min. 8 characters)'"
          />
        </div>

        <!-- Password Confirmation Field (Register Only) -->
        <div v-if="!isLogin">
          <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
            Confirm Password
          </label>
          <input
            id="password_confirmation"
            v-model="form.password_confirmation"
            type="password"
            required
            class="appearance-none relative block w-full px-4 py-3 border border-gray-300 rounded-lg placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm transition"
            placeholder="Confirm your password"
          />
        </div>

        <!-- Submit Button -->
        <div>
          <button
            type="submit"
            :disabled="loading"
            class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed transition"
          >
            {{ loading ? 'Processing...' : (isLogin ? 'Sign In' : 'Sign Up') }}
          </button>
        </div>
      </form>

      <!-- Toggle Between Login/Register -->
      <div class="text-center">
        <p class="text-sm text-gray-600">
          {{ isLogin ? "Don't have an account?" : 'Already have an account?' }}
          <button
            @click="toggleMode"
            class="font-medium text-indigo-600 hover:text-indigo-500 transition"
          >
            {{ isLogin ? 'Sign Up' : 'Sign In' }}
          </button>
        </p>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'AuthPage',
  data() {
    return {
      isLogin: true,
      loading: false,
      error: '',
      success: '',
      form: {
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
      },
    };
  },
  methods: {
    toggleMode() {
      this.isLogin = !this.isLogin;
      this.error = '';
      this.success = '';
      this.form = {
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
      };
    },
    async handleSubmit() {
      this.loading = true;
      this.error = '';
      this.success = '';

      try {
        const endpoint = this.isLogin ? '/api/auth/login' : '/api/auth/register';
        const payload = this.isLogin
          ? {
              email: this.form.email,
              password: this.form.password,
            }
          : this.form;

        const response = await axios.post(endpoint, payload);

        // Store token
        localStorage.setItem('auth_token', response.data.token);

        // Set default auth header
        axios.defaults.headers.common['Authorization'] = `Bearer ${response.data.token}`;

        this.success = response.data.message;
        
        // Redirect after a short delay
        setTimeout(() => {
          window.location.href = '/dashboard';
        }, 1500);
      } catch (err) {
        if (err.response?.data?.errors) {
          // Handle Laravel validation errors
          const errors = err.response.data.errors;
          const firstError = Object.values(errors)[0];
          this.error = Array.isArray(firstError) ? firstError[0] : firstError;
        } else {
          this.error = err.response?.data?.message || 'An error occurred. Please try again.';
        }
      } finally {
        this.loading = false;
      }
    },
  },
};
</script>

