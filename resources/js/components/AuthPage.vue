<template>
  <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 via-white to-cyan-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 px-4 py-12">
    <div class="w-full max-w-md">
      <!-- Logo/Header -->
      <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-600 rounded-2xl mb-4">
          <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
          </svg>
        </div>
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
          სამედიცინო პორტალი
        </h1>
        <p class="text-gray-600 dark:text-gray-400">
          შედით თქვენს ანგარიშში
        </p>
      </div>

      <!-- Login Card -->
      <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8">
        <div v-if="error" class="mb-4 p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
          <p class="text-sm text-red-800 dark:text-red-300">{{ error }}</p>
        </div>
        
        <form @submit.prevent="handleSubmit" class="space-y-6">
          <!-- Email Field -->
          <Input
            id="email"
            type="email"
            label="ელ. ფოსტა"
            v-model="form.email"
            placeholder="example@email.com"
            required
          >
            <template #icon>
              <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
              </svg>
            </template>
          </Input>

          <!-- Password Field -->
          <PasswordInput
            id="password"
            label="პაროლი"
            v-model="form.password"
            placeholder="••••••••"
            required
          />

          <!-- Remember Me & Forgot Password -->
          <div class="flex items-center justify-between">
            <Checkbox
              id="remember-me"
              label="დამახსოვრება"
              v-model="rememberMe"
            />
            <a href="/forgot-password" class="text-sm font-medium text-blue-600 hover:text-blue-500 dark:text-blue-400">
              დაგავიწყდა პაროლი?
            </a>
          </div>

          <!-- Submit Button -->
          <Button type="submit" variant="primary" :full-width="true" :disabled="loading">
            {{ loading ? 'იტვირთება...' : 'შესვლა' }}
          </Button>
        </form>


        <!-- Sign Up Link -->
        <p class="mt-6 text-center text-sm text-gray-600 dark:text-gray-400">
          არ გაქვთ ანგარიში? 
          <a href="/register" class="font-medium text-blue-600 hover:text-blue-500 dark:text-blue-400">
            დარეგისტრირდით
          </a>
        </p>
      </div>
    </div>
  </div>
</template>

<script>
import { useAuthStore } from '../stores/auth';
import axios from 'axios';
import Button from './ui/Button.vue';
import Input from './ui/Input.vue';
import PasswordInput from './ui/PasswordInput.vue';
import Checkbox from './ui/Checkbox.vue';
import Divider from './ui/Divider.vue';

export default {
  name: 'AuthPage',
  components: {
    Button,
    Input,
    PasswordInput,
    Checkbox,
    Divider
  },
  setup() {
    const authStore = useAuthStore();
    return { authStore };
  },
  data() {
    return {
      loading: false,
      error: '',
      rememberMe: false,
      form: {
        email: '',
        password: '',
      },
    };
  },
  methods: {
    async handleSubmit() {
      this.loading = true;
      this.error = '';

      try {
        const response = await axios.post('/api/auth/login', this.form);
        
        // Use auth store
        this.authStore.setAuth(response.data.user, response.data.token);
        
        this.$router.push('/dashboard');
      } catch (err) {
        this.error = err.response?.data?.message || 'შეცდომა მოხდა';
      } finally {
        this.loading = false;
      }
    },
  },
};
</script>
