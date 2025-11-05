<template>
  <nav class="bg-white dark:bg-gray-800 shadow-lg border-b border-gray-200 dark:border-gray-700">
    <div class="max-w-[1485px] mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between h-16">
        <!-- Logo and Nav Items -->
        <div class="flex">
          <!-- Logo -->
          <router-link :to="authStore.userRole === 'LABOR' ? '/visits' : '/dashboard'" class="flex items-center gap-3">
            <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
              </svg>
            </div>
            <span class="text-xl font-bold text-gray-900 dark:text-white hidden sm:block">
              სამედიცინო პორტალი
            </span>
          </router-link>

          <!-- Desktop Navigation -->
          <div class="hidden md:ml-10 md:flex md:space-x-2">
            <router-link
              v-for="item in navItems"
              :key="item.path"
              :to="item.path"
              :class="[
                'inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium transition-colors',
                isActive(item.path)
                  ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200'
                  : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'
              ]"
            >
              <span class="w-5 h-5" v-html="item.icon"></span>
              {{ item.name }}
            </router-link>
          </div>
        </div>

        <!-- User Menu -->
        <div class="flex items-center gap-4">
          <div v-if="authStore.user" class="hidden md:flex items-center gap-2 text-sm">
            <div class="text-right">
              <div class="font-medium text-gray-900 dark:text-white">
                {{ authStore.user.first_name }} {{ authStore.user.last_name }}
              </div>
              <div class="text-xs text-gray-500 dark:text-gray-400">
                {{ getRoleLabel(authStore.user.role) }}
              </div>
            </div>
            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold">
              {{ authStore.user.first_name[0] }}{{ authStore.user.last_name[0] }}
            </div>
          </div>
          <Button
            v-if="authStore.user"
            variant="secondary"
            @click="handleLogout"
            class="hidden md:flex"
          >
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
            </svg>
            გასვლა
          </Button>

          <!-- Mobile menu button -->
          <button
            @click="toggleMobileMenu"
            class="md:hidden inline-flex items-center justify-center p-2 rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"
          >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path v-if="mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
          </button>
        </div>
      </div>
    </div>

    <!-- Mobile menu -->
    <div v-if="mobileMenuOpen" class="md:hidden border-t border-gray-200 dark:border-gray-700">
      <div class="px-2 pt-2 pb-3 space-y-1">
        <router-link
          v-for="item in navItems"
          :key="item.path"
          :to="item.path"
          @click="closeMobileMenu"
          :class="[
            'flex items-center gap-3 px-3 py-2 rounded-lg text-base font-medium',
            isActive(item.path)
              ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200'
              : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'
          ]"
        >
          <span class="w-5 h-5" v-html="item.icon"></span>
          {{ item.name }}
        </router-link>
      </div>
      <div v-if="authStore.user" class="border-t border-gray-200 dark:border-gray-700 px-4 py-4">
        <div class="flex items-center gap-3 mb-3">
          <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold">
            {{ authStore.user.first_name[0] }}{{ authStore.user.last_name[0] }}
          </div>
          <div>
            <div class="font-medium text-gray-900 dark:text-white">
              {{ authStore.user.first_name }} {{ authStore.user.last_name }}
            </div>
            <div class="text-xs text-gray-500 dark:text-gray-400">
              {{ getRoleLabel(authStore.user.role) }}
            </div>
          </div>
        </div>
        <button
          @click="handleLogout"
          class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition"
        >
          <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
            </svg>
           გასვლა
          </button>
      </div>
    </div>
  </nav>
</template>

<script>
import { useAuthStore } from '../stores/auth';
import axios from 'axios';
import Button from './ui/Button.vue';

export default {
  name: 'Navbar',
  components: {
    Button
  },
  setup() {
    const authStore = useAuthStore();
    return { authStore };
  },
  data() {
    return {
      mobileMenuOpen: false,
      allNavItems: [
        {
          name: 'Dashboard',
          path: '/dashboard',
          icon: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>',
        },
        {
          name: 'მომხმარებლები',
          path: '/users',
          icon: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>',
        },
        {
          name: 'პაციენტები',
          path: '/patients',
          icon: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>',
        },
        {
          name: 'ვიზიტები',
          path: '/visits',
          icon: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>',
        },
        {
          name: 'გადახდები',
          path: '/payments',
          icon: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>',
        },
      ]
    };
  },
  computed: {
    navItems() {
      // If user is labor, show only visits page
      if (this.authStore.userRole === 'LABOR') {
        return this.allNavItems.filter(item => item.path === '/visits');
      }
      // Otherwise show all items
      return this.allNavItems;
    }
  },
  mounted() {
    // Load user from storage
    this.authStore.loadFromStorage();
  },
  methods: {
    isActive(path) {
      return this.$route.path === path;
    },
    toggleMobileMenu() {
      this.mobileMenuOpen = !this.mobileMenuOpen;
    },
    closeMobileMenu() {
      this.mobileMenuOpen = false;
    },
    getRoleLabel(role) {
      const labels = {
        'PATIENT': 'პაციენტი',
        'DOCTOR': 'ექიმი',
        'ADMIN': 'ადმინი',
        'LABOR': 'ლაბორანტი'
      };
      return labels[role] || role;
    },
    async handleLogout() {
      try {
        if (this.authStore.token) {
          await axios.post('/api/auth/logout', {}, {
            headers: {
              'Authorization': `Bearer ${this.authStore.token}`
            }
          });
        }
      } catch (error) {
        console.error('Logout error:', error);
      } finally {
        this.authStore.logout();
        this.$router.push('/');
      }
    }
  }
};
</script>
