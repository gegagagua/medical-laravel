<template>
  <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 via-white to-cyan-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 px-4 py-12">
    <div class="w-full max-w-2xl">
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
          შექმენით ახალი ანგარიში
        </p>
      </div>

      <!-- Registration Card -->
      <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8">
        <div v-if="error" class="mb-4 p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
          <p class="text-sm text-red-800 dark:text-red-300">{{ error }}</p>
        </div>
        
        <form @submit.prevent="handleSubmit" class="space-y-6">
          <!-- Name Fields -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <Input
              id="firstName"
              name="firstName"
              type="text"
              label="სახელი"
              v-model="formData.firstName"
              placeholder="გიორგი"
              required
            >
              <template #icon>
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
              </template>
            </Input>

            <Input
              id="lastName"
              name="lastName"
              type="text"
              label="გვარი"
              v-model="formData.lastName"
              placeholder="მამულაშვილი"
              required
            >
              <template #icon>
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
              </template>
            </Input>
          </div>

          <!-- Email Field -->
          <Input
            id="email"
            name="email"
            type="email"
            label="ელ. ფოსტა"
            v-model="formData.email"
            placeholder="example@email.com"
            required
          >
            <template #icon>
              <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
              </svg>
            </template>
          </Input>

          <!-- Phone Field -->
          <Input
            id="phone"
            name="phone"
            type="tel"
            label="ტელეფონი"
            v-model="formData.phone"
            placeholder="+995 555 123 456"
            required
          >
            <template #icon>
              <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
              </svg>
            </template>
          </Input>

          <!-- Password Fields -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <PasswordInput
              id="password"
              name="password"
              label="პაროლი"
              v-model="formData.password"
              placeholder="••••••••"
              required
            />

            <PasswordInput
              id="confirmPassword"
              name="confirmPassword"
              label="გაიმეორეთ პაროლი"
              v-model="formData.confirmPassword"
              placeholder="••••••••"
              required
            />
          </div>

          <!-- Password Requirements -->
          <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
            <p class="text-xs text-blue-800 dark:text-blue-300 mb-2 font-medium">პაროლის მოთხოვნები:</p>
            <ul class="text-xs text-blue-700 dark:text-blue-400 space-y-1 list-disc list-inside">
              <li>მინიმუმ 8 სიმბოლო</li>
              <li>სასურველია შეიცავდეს დიდ და პატარა ასოებს</li>
              <li>სასურველია შეიცავდეს რიცხვებს და სპეც. სიმბოლოებს</li>
            </ul>
          </div>

          <!-- Terms and Conditions -->
          <Checkbox
            id="terms"
            v-model="acceptTerms"
            required
          >
            <template #label>
              ვეთანხმები
              <a href="/terms" class="font-medium text-blue-600 hover:text-blue-500 dark:text-blue-400">
                წესებსა და პირობებს
              </a>
              და
              <a href="/privacy" class="font-medium text-blue-600 hover:text-blue-500 dark:text-blue-400">
                კონფიდენციალურობის პოლიტიკას
              </a>
            </template>
          </Checkbox>

          <!-- Submit Button -->
          <Button type="submit" variant="primary" :full-width="true" :disabled="loading">
            {{ loading ? 'იტვირთება...' : 'რეგისტრაცია' }}
          </Button>
        </form>

        <!-- Login Link -->
        <p class="mt-6 text-center text-sm text-gray-600 dark:text-gray-400">
          უკვე გაქვთ ანგარიში?
          <button @click="$router.push('/auth')" class="font-medium text-blue-600 hover:text-blue-500 dark:text-blue-400">
            შესვლა
          </button>
        </p>
      </div>
    </div>
  </div>
</template>

<script>
import { useAuthStore } from '../stores/auth';
import axios from 'axios';
import Input from './ui/Input.vue';
import PasswordInput from './ui/PasswordInput.vue';
import Button from './ui/Button.vue';
import Checkbox from './ui/Checkbox.vue';
import Divider from './ui/Divider.vue';

export default {
  name: 'RegisterPage',
  components: {
    Input,
    PasswordInput,
    Button,
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
      acceptTerms: false,
      formData: {
        firstName: '',
        lastName: '',
        email: '',
        phone: '',
        password: '',
        confirmPassword: ''
      }
    };
  },
  methods: {
    async handleSubmit() {
      this.loading = true;
      this.error = '';

      // Validate passwords match
      if (this.formData.password !== this.formData.confirmPassword) {
        this.error = 'პაროლები არ ემთხვევა';
        this.loading = false;
        return;
      }

      // Validate password length
      if (this.formData.password.length < 8) {
        this.error = 'პაროლი უნდა შეიცავდეს მინიმუმ 8 სიმბოლოს';
        this.loading = false;
        return;
      }

      // Validate terms acceptance
      if (!this.acceptTerms) {
        this.error = 'გთხოვთ დაეთანხმოთ წესებსა და პირობებს';
        this.loading = false;
        return;
      }

           try {
             // Transform data to match API expectations
             const submitData = {
               first_name: this.formData.firstName,
               last_name: this.formData.lastName,
               email: this.formData.email,
               phone: this.formData.phone,
               password: this.formData.password,
               password_confirmation: this.formData.confirmPassword
             };

             const response = await axios.post('/api/auth/register', submitData);
             
             // Use auth store
             this.authStore.setAuth(response.data.user, response.data.token);
             
             this.$router.push('/dashboard');
      } catch (err) {
        this.error = err.response?.data?.message || 'რეგისტრაციისას შეცდომა მოხდა';
      } finally {
        this.loading = false;
      }
    }
  }
};
</script>
