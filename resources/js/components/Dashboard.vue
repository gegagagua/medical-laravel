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

      <!-- Doctor Payments Section -->
      <div v-if="authStore.user && authStore.user.role === 'DOCTOR'" class="mb-8">
        <!-- Payment Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
          <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">მთლიანი შემოსავალი</p>
                <p class="text-3xl font-bold text-green-600 dark:text-green-400">
                  ₾{{ totalRevenue.toFixed(2) }}
                </p>
              </div>
              <div class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
            </div>
          </div>

          <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">დღევანდელი შემოსავალი</p>
                <p class="text-3xl font-bold text-blue-600 dark:text-blue-400">
                  ₾{{ todayRevenue.toFixed(2) }}
                </p>
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
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">გადახდილი</p>
                <p class="text-3xl font-bold text-green-600 dark:text-green-400">{{ paidCount }}</p>
              </div>
              <div class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
            </div>
          </div>

          <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">მოლოდინში</p>
                <p class="text-3xl font-bold text-orange-600 dark:text-orange-400">{{ pendingCount }}</p>
              </div>
              <div class="w-12 h-12 bg-orange-100 dark:bg-orange-900 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
            </div>
          </div>
        </div>

        <!-- Tabs for Full List and Daily Breakdown -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6">
          <div class="mb-6">
            <div class="flex border-b border-gray-200 dark:border-gray-700">
              <button
                @click="activeTab = 'full'"
                :class="[
                  'px-6 py-3 font-medium text-sm transition-colors',
                  activeTab === 'full'
                    ? 'border-b-2 border-blue-500 text-blue-600 dark:text-blue-400'
                    : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300'
                ]"
              >
                სრული სია
              </button>
              <button
                @click="activeTab = 'daily'"
                :class="[
                  'px-6 py-3 font-medium text-sm transition-colors',
                  activeTab === 'daily'
                    ? 'border-b-2 border-blue-500 text-blue-600 dark:text-blue-400'
                    : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300'
                ]"
              >
                დღიური ჭრილი
              </button>
            </div>
          </div>

          <!-- Full List Tab -->
          <div v-if="activeTab === 'full'">
            <div class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900">
                  <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">ინვოისი</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">პაციენტი</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">სერვისი</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">თანხა</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">გადახდის მეთოდი</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">სტატუსი</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">თარიღი</th>
                  </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                  <tr v-if="loading" class="text-center">
                    <td colspan="7" class="px-6 py-4 text-gray-500 dark:text-gray-400">იტვირთება...</td>
                  </tr>
                  <tr v-else-if="payments.length === 0" class="text-center">
                    <td colspan="7" class="px-6 py-4 text-gray-500 dark:text-gray-400">გადახდები არ მოიძებნა</td>
                  </tr>
                  <tr v-else v-for="payment in payments" :key="payment.id" class="hover:bg-gray-50 dark:hover:bg-gray-700">
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span class="font-mono font-semibold text-blue-600 dark:text-blue-400">{{ payment.invoiceNumber }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span class="font-medium text-gray-900 dark:text-white">{{ payment.patientName }}</span>
                    </td>
                    <td class="px-6 py-4">
                      <span class="text-sm text-gray-600 dark:text-gray-400">{{ payment.service }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span class="font-bold text-green-600 dark:text-green-400">₾{{ Number(payment.amount).toFixed(2) }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span :class="getPaymentMethodClass(payment.paymentMethod)">
                        {{ getPaymentMethodLabel(payment.paymentMethod) }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span :class="getStatusClass(payment.status)">
                        {{ getStatusLabel(payment.status) }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span class="text-sm text-gray-600 dark:text-gray-400">{{ formatDate(payment.date) }}</span>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Daily Breakdown Tab -->
          <div v-if="activeTab === 'daily'">
            <div class="space-y-4">
              <div v-if="loading" class="text-center text-gray-500 dark:text-gray-400 py-8">იტვირთება...</div>
              <div v-else-if="dailyPayments.length === 0" class="text-center text-gray-500 dark:text-gray-400 py-8">გადახდები არ მოიძებნა</div>
              <div v-else v-for="day in dailyPayments" :key="day.date" class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                <div class="flex items-center justify-between mb-4">
                  <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ formatDate(day.date) }}</h3>
                  <span class="text-xl font-bold text-green-600 dark:text-green-400">₾{{ day.total.toFixed(2) }}</span>
                </div>
                <div class="space-y-2">
                  <div v-for="payment in day.payments" :key="payment.id" class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-900 rounded-lg">
                    <div class="flex-1">
                      <p class="font-medium text-gray-900 dark:text-white">{{ payment.patientName }}</p>
                      <p class="text-sm text-gray-600 dark:text-gray-400">{{ payment.service }}</p>
                      <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">{{ payment.invoiceNumber }}</p>
                    </div>
                    <div class="text-right">
                      <p class="font-bold text-green-600 dark:text-green-400">₾{{ Number(payment.amount).toFixed(2) }}</p>
                      <span :class="getStatusClass(payment.status)" class="text-xs">
                        {{ getStatusLabel(payment.status) }}
                      </span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
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
import axios from 'axios';
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
  data() {
    return {
      payments: [],
      loading: false,
      activeTab: 'full'
    };
  },
  computed: {
    totalRevenue() {
      return this.payments
        .filter(p => p.status === 'paid')
        .reduce((sum, p) => sum + Number(p.amount), 0);
    },
    todayRevenue() {
      const today = new Date().toISOString().split('T')[0];
      return this.payments
        .filter(p => {
          const paymentDate = new Date(p.date).toISOString().split('T')[0];
          return paymentDate === today && p.status === 'paid';
        })
        .reduce((sum, p) => sum + Number(p.amount), 0);
    },
    paidCount() {
      return this.payments.filter(p => p.status === 'paid').length;
    },
    pendingCount() {
      return this.payments.filter(p => p.status === 'pending').length;
    },
    dailyPayments() {
      const grouped = {};
      this.payments.forEach(payment => {
        const date = new Date(payment.date).toISOString().split('T')[0];
        if (!grouped[date]) {
          grouped[date] = {
            date: date,
            total: 0,
            payments: []
          };
        }
        grouped[date].payments.push(payment);
        if (payment.status === 'paid') {
          grouped[date].total += Number(payment.amount);
        }
      });
      return Object.values(grouped)
        .sort((a, b) => new Date(b.date) - new Date(a.date))
        .map(day => ({
          ...day,
          total: day.payments
            .filter(p => p.status === 'paid')
            .reduce((sum, p) => sum + Number(p.amount), 0)
        }));
    }
  },
  mounted() {
    // Load user from storage on mount
    this.authStore.loadFromStorage();
    
    // Fetch payments if user is a doctor
    if (this.authStore.user && this.authStore.user.role === 'DOCTOR') {
      this.fetchPayments();
    }
  },
  methods: {
    async fetchPayments() {
      this.loading = true;
      try {
        const token = localStorage.getItem('auth_token');
        const response = await axios.get('/api/payments/by-doctor', {
          headers: { 'Authorization': `Bearer ${token}` }
        });
        this.payments = response.data;
      } catch (error) {
        console.error('Failed to fetch payments:', error);
        this.payments = [];
      } finally {
        this.loading = false;
      }
    },
    formatDate(dateString) {
      if (!dateString) return '-';
      const date = new Date(dateString);
      return date.toLocaleDateString('ka-GE', { 
        year: 'numeric', 
        month: 'short', 
        day: 'numeric' 
      });
    },
    getPaymentMethodLabel(method) {
      const methods = {
        cash: 'ნაღდი',
        card: 'ბარათი',
        transfer: 'გადარიცხვა'
      };
      return methods[method] || method;
    },
    getPaymentMethodClass(method) {
      const classes = {
        cash: 'px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
        card: 'px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200',
        transfer: 'px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200'
      };
      return classes[method] || 'px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200';
    },
    getStatusLabel(status) {
      const statuses = {
        paid: 'გადახდილი',
        pending: 'მოლოდინში',
        cancelled: 'გაუქმებული'
      };
      return statuses[status] || status;
    },
    getStatusClass(status) {
      const classes = {
        paid: 'px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
        pending: 'px-3 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200',
        cancelled: 'px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'
      };
      return classes[status] || 'px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200';
    }
  }
};
</script>
