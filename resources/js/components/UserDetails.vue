<template>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <Navbar />
    
    <main class="max-w-[1485px] mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Back Button -->
      <div class="mb-6">
        <Button variant="secondary" @click="goBack">
          <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
          </svg>
          უკან
        </Button>
      </div>

      <!-- Loading State -->
      <div v-if="loading" class="flex justify-center items-center py-12">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
      </div>

      <!-- User Details -->
      <div v-else-if="user" class="space-y-6">
        <!-- User Info Card -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6">
          <div class="flex items-start justify-between mb-6">
            <div>
              <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
                {{ user.first_name }} {{ user.last_name }}
              </h1>
              <div class="flex items-center gap-4 text-sm text-gray-600 dark:text-gray-400">
                <span>ID: {{ user.id }}</span>
                <span v-if="user.role" :class="getRoleBadgeClass(user.role)">
                  {{ getRoleLabel(user.role) }}
                </span>
                <span v-if="user.doctor_role" class="px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                  {{ user.doctor_role }}
                </span>
              </div>
            </div>
            <div class="flex gap-3">
              <Button variant="secondary" @click="printUserInfo">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                დაბეჭდვა
              </Button>
            </div>
          </div>

          <!-- User Details Grid -->
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div>
              <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
                ელ. ფოსტა
              </label>
              <p class="text-lg text-gray-900 dark:text-white">
                <a :href="`mailto:${user.email}`" class="text-blue-600 hover:text-blue-800 dark:text-blue-400">
                  {{ user.email }}
                </a>
              </p>
            </div>

            <div v-if="user.phone">
              <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
                ტელეფონი
              </label>
              <p class="text-lg text-gray-900 dark:text-white">
                <a :href="`tel:${user.phone}`" class="text-blue-600 hover:text-blue-800 dark:text-blue-400">
                  {{ user.phone }}
                </a>
              </p>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
                რეგისტრაციის თარიღი
              </label>
              <p class="text-lg text-gray-900 dark:text-white">
                {{ formatDate(user.created_at) }}
              </p>
            </div>
          </div>
        </div>

        <!-- Payments Section (for Doctors) -->
        <div v-if="user.role !== 'admin'" class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 mb-6">
          <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">
            გადახდები
          </h2>

          <!-- Payment Stats -->
          <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-4">
              <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">მთლიანი შემოსავალი</p>
              <p class="text-2xl font-bold text-green-600 dark:text-green-400">
                ₾{{ totalRevenue.toFixed(2) }}
              </p>
            </div>
            <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-4">
              <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">დღევანდელი შემოსავალი</p>
              <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">
                ₾{{ todayRevenue.toFixed(2) }}
              </p>
            </div>
            <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-4">
              <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">გადახდილი</p>
              <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ paidCount }}</p>
            </div>
            <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-4">
              <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">მოლოდინში</p>
              <p class="text-2xl font-bold text-orange-600 dark:text-orange-400">{{ pendingCount }}</p>
            </div>
          </div>

          <!-- Tabs for Full List and Daily Breakdown -->
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
            <div v-if="paymentsLoading" class="text-center py-8 text-gray-500 dark:text-gray-400">
              იტვირთება...
            </div>
            <div v-else-if="payments.length === 0" class="text-center py-12">
              <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
              </svg>
              <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">გადახდები არ მოიძებნა</h3>
              <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">ამ ექიმს ჯერ არ ჰქონია გადახდები</p>
            </div>
            <div v-else class="overflow-x-auto">
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
                  <tr v-for="payment in payments" :key="payment.id" class="hover:bg-gray-50 dark:hover:bg-gray-700">
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
                      <span :class="getPaymentStatusClass(payment.status)">
                        {{ getPaymentStatusLabel(payment.status) }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span class="text-sm text-gray-600 dark:text-gray-400">{{ formatPaymentDate(payment.date) }}</span>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Daily Breakdown Tab -->
          <div v-if="activeTab === 'daily'">
            <div v-if="paymentsLoading" class="text-center py-8 text-gray-500 dark:text-gray-400">
              იტვირთება...
            </div>
            <div v-else-if="dailyPayments.length === 0" class="text-center py-12">
              <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
              </svg>
              <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">გადახდები არ მოიძებნა</h3>
            </div>
            <div v-else class="space-y-4">
              <div v-for="day in dailyPayments" :key="day.date" class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                <div class="flex items-center justify-between mb-4">
                  <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ formatPaymentDate(day.date) }}</h3>
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
                      <span :class="getPaymentStatusClass(payment.status)" class="text-xs">
                        {{ getPaymentStatusLabel(payment.status) }}
                      </span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Visit History -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6">
          <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">
            ვიზიტების ისტორია
          </h2>

          <!-- Empty State -->
          <div v-if="visits.length === 0" class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">ვიზიტები არ მოიძებნა</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">ამ მომხმარებელს ჯერ არ ჰქონია ვიზიტები</p>
          </div>

          <!-- Visits List -->
          <div v-else class="space-y-4">
            <div
              v-for="visit in visits"
              :key="visit.id"
              class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors cursor-pointer"
              @click="goToPatient(visit.patient_id)"
            >
              <div class="flex items-start justify-between">
                <div class="flex-1">
                  <div class="flex items-center gap-3 mb-2">
                    <span class="font-semibold text-gray-900 dark:text-white">
                      {{ formatDate(visit.date) }} - {{ visit.time }}
                    </span>
                    <span :class="getStatusBadgeClass(visit.status)">
                      {{ getStatusLabel(visit.status) }}
                    </span>
                  </div>
                  
                  <div v-if="visit.patientName" class="text-sm text-gray-600 dark:text-gray-400 mb-1">
                    <span class="font-medium">პაციენტი:</span> {{ visit.patientName }}
                  </div>
                  
                  <div v-if="visit.department" class="text-sm text-gray-600 dark:text-gray-400 mb-1">
                    <span class="font-medium">განყოფილება:</span> {{ visit.department }}
                  </div>
                  
                  <div v-if="visit.notes" class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                    <span class="font-medium">შენიშვნა:</span> {{ visit.notes }}
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Error State -->
      <div v-else class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">მომხმარებელი არ მოიძებნა</h3>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ error || 'შეცდომა მოხდა მომხმარებლის ჩატვირთვისას' }}</p>
      </div>
    </main>
  </div>
</template>

<script>
import axios from 'axios';
import Navbar from './Navbar.vue';
import Button from './ui/Button.vue';

export default {
  name: 'UserDetails',
  components: {
    Navbar,
    Button
  },
  data() {
    return {
      user: null,
      visits: [],
      payments: [],
      paymentsLoading: false,
      loading: true,
      error: '',
      activeTab: 'full'
    };
  },
  computed: {
    totalRevenue() {
      if (!this.payments || this.payments.length === 0) return 0;
      return this.payments
        .filter(p => p.status === 'paid')
        .reduce((sum, p) => sum + Number(p.amount || 0), 0);
    },
    todayRevenue() {
      if (!this.payments || this.payments.length === 0) return 0;
      const today = new Date().toISOString().split('T')[0];
      return this.payments
        .filter(p => {
          if (!p.date) return false;
          const paymentDate = new Date(p.date).toISOString().split('T')[0];
          return paymentDate === today && p.status === 'paid';
        })
        .reduce((sum, p) => sum + Number(p.amount || 0), 0);
    },
    paidCount() {
      if (!this.payments || this.payments.length === 0) return 0;
      return this.payments.filter(p => p.status === 'paid').length;
    },
    pendingCount() {
      if (!this.payments || this.payments.length === 0) return 0;
      return this.payments.filter(p => p.status === 'pending').length;
    },
    dailyPayments() {
      if (!this.payments || this.payments.length === 0) return [];
      const grouped = {};
      this.payments.forEach(payment => {
        if (!payment.date) return;
        const date = new Date(payment.date).toISOString().split('T')[0];
        if (!grouped[date]) {
          grouped[date] = {
            date: date,
            total: 0,
            payments: []
          };
        }
        grouped[date].payments.push(payment);
      });
      return Object.values(grouped)
        .sort((a, b) => new Date(b.date) - new Date(a.date))
        .map(day => ({
          ...day,
          total: day.payments
            .filter(p => p.status === 'paid')
            .reduce((sum, p) => sum + Number(p.amount || 0), 0)
        }));
    }
  },
  watch: {
    // Watch for user changes and fetch payments when user is loaded
    user: {
      handler(newUser) {
        if (newUser && newUser.role === 'DOCTOR') {
          console.log('User changed, fetching payments for doctor:', newUser);
          this.fetchPayments();
        }
      },
      immediate: false
    }
  },
  async mounted() {
    await this.fetchUserDetails();
    await this.fetchUserVisits();
    // Fetch payments if user is a doctor
    if (this.user && this.user.role === 'DOCTOR') {
      console.log('User is a doctor, fetching payments for:', this.user);
      await this.fetchPayments();
    } else {
      console.log('User is not a doctor or user not loaded:', this.user);
    }
  },
  methods: {
    async fetchUserDetails() {
      this.loading = true;
      this.error = '';
      
      try {
        const userId = this.$route.params.id;
        const token = localStorage.getItem('auth_token');
        const response = await axios.get(`/api/users/${userId}`, {
          headers: { 'Authorization': `Bearer ${token}` }
        });
        this.user = response.data;
      } catch (error) {
        console.error('Failed to fetch user:', error);
        this.error = error.response?.data?.message || 'მომხმარებლის ჩატვირთვა ვერ მოხერხდა';
      } finally {
        this.loading = false;
      }
    },
    async fetchUserVisits() {
      try {
        // Wait for user to be loaded
        if (!this.user) {
          await this.fetchUserDetails();
        }
        
        if (!this.user) return;
        
        const token = localStorage.getItem('auth_token');
        const response = await axios.get('/api/visits', {
          headers: { 'Authorization': `Bearer ${token}` }
        });
        
        const userFullName = `${this.user.first_name} ${this.user.last_name}`;
        
        // Filter visits where doctor_name matches this user's full name
        this.visits = response.data.filter(visit => 
          visit.doctorName === userFullName
        ).sort((a, b) => new Date(b.date) - new Date(a.date));
      } catch (error) {
        console.error('Failed to fetch visits:', error);
      }
    },
    async fetchPayments() {
      if (!this.user) {
        // Wait for user to be loaded
        await this.fetchUserDetails();
      }
      
      if (!this.user || this.user.role !== 'DOCTOR') {
        this.payments = [];
        return;
      }
      
      this.paymentsLoading = true;
      try {
        const token = localStorage.getItem('auth_token');
        const userId = this.user.id;
        
        console.log('Fetching payments for user ID:', userId);
        
        // Fetch all payments and filter by user_id (more reliable than doctor name)
        const response = await axios.get('/api/payments', {
          headers: { 'Authorization': `Bearer ${token}` }
        });
        
        console.log('All payments from API:', response.data);
        console.log('Looking for user_id:', userId);
        
        // Filter payments where this user created the payment (by user_id)
        const allPayments = response.data || [];
        console.log('Total payments received:', allPayments.length);
        
        this.payments = allPayments
          .filter(payment => {
            // Check both user_id and doctor name for backward compatibility
            const matchesUserId = payment.userId && Number(payment.userId) === Number(userId);
            const matchesDoctorName = payment.doctor && 
              payment.doctor.trim().toLowerCase() === `${this.user.first_name} ${this.user.last_name}`.trim().toLowerCase();
            
            const matches = matchesUserId || matchesDoctorName;
            
            if (matches) {
              console.log('✓ Found matching payment:', payment);
            }
            return matches;
          })
          .sort((a, b) => new Date(b.date) - new Date(a.date));
        
        console.log('Filtered payments count:', this.payments.length);
        console.log('Filtered payments:', this.payments);
      } catch (error) {
        console.error('Failed to fetch payments:', error);
        this.payments = [];
      } finally {
        this.paymentsLoading = false;
      }
    },
    goBack() {
      this.$router.push('/users');
    },
    goToPatient(patientId) {
      if (patientId) {
        this.$router.push(`/patients/${patientId}`);
      }
    },
    formatDate(dateString) {
      if (!dateString) return '-';
      const date = new Date(dateString);
      return date.toLocaleDateString('ka-GE', { 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric' 
      });
    },
    formatPaymentDate(dateString) {
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
    getPaymentStatusLabel(status) {
      const statuses = {
        paid: 'გადახდილი',
        pending: 'მოლოდინში',
        cancelled: 'გაუქმებული'
      };
      return statuses[status] || status;
    },
    getPaymentStatusClass(status) {
      const classes = {
        paid: 'px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
        pending: 'px-3 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200',
        cancelled: 'px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'
      };
      return classes[status] || 'px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200';
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
    getRoleBadgeClass(role) {
      const classes = {
        'PATIENT': 'px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
        'DOCTOR': 'px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
        'ADMIN': 'px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200',
        'LABOR': 'px-2 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200'
      };
      return classes[role] || 'px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200';
    },
    getStatusLabel(status) {
      const statuses = {
        'PENDING': 'მოლოდინში',
        'CONFIRMED': 'დადასტურებული',
        'CANCELLED': 'გაუქმებული',
        'COMPLETED': 'დასრულებული',
        pending: 'მოლოდინში',
        confirmed: 'დადასტურებული',
        cancelled: 'გაუქმებული',
        completed: 'დასრულებული'
      };
      return statuses[status] || status;
    },
    getStatusBadgeClass(status) {
      const statusUpper = status?.toUpperCase();
      const classes = {
        'PENDING': 'px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
        'CONFIRMED': 'px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
        'CANCELLED': 'px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
        'COMPLETED': 'px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200'
      };
      return classes[statusUpper] || 'px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200';
    },
    printUserInfo() {
      // Create print window content
      const printWindow = window.open('', '_blank');
      
      const printContent = `
          <!DOCTYPE html>
          <html>
            <head>
              <title>მომხმარებლის ინფორმაცია - ${this.user.first_name} ${this.user.last_name}</title>
              <style>
                @page {
                  size: A4;
                  margin: 2cm;
                }
                body {
                  font-family: Arial, sans-serif;
                  font-size: 12px;
                  line-height: 1.6;
                  color: #000;
                  margin: 0;
                  padding: 20px;
                }
                .header {
                  text-align: center;
                  border-bottom: 2px solid #000;
                  padding-bottom: 20px;
                  margin-bottom: 30px;
                }
                .header h1 {
                  margin: 0;
                  font-size: 24px;
                  color: #000;
                }
                .section {
                  margin-bottom: 30px;
                }
                .section-title {
                  font-size: 18px;
                  font-weight: bold;
                  margin-bottom: 15px;
                  border-bottom: 1px solid #ccc;
                  padding-bottom: 5px;
                }
                .info-grid {
                  display: grid;
                  grid-template-columns: 1fr 2fr;
                  gap: 10px;
                  margin-bottom: 15px;
                }
                .info-label {
                  font-weight: bold;
                  color: #333;
                }
                .info-value {
                  color: #000;
                }
                table {
                  width: 100%;
                  border-collapse: collapse;
                  margin-top: 15px;
                }
                th, td {
                  border: 1px solid #ccc;
                  padding: 8px;
                  text-align: left;
                }
                th {
                  background-color: #f0f0f0;
                  font-weight: bold;
                }
                tr:nth-child(even) {
                  background-color: #f9f9f9;
                }
                .no-visits {
                  text-align: center;
                  padding: 20px;
                  color: #666;
                  font-style: italic;
                }
                .print-date {
                  text-align: right;
                  color: #666;
                  font-size: 10px;
                  margin-top: 30px;
                  padding-top: 20px;
                  border-top: 1px solid #ccc;
                }
              </style>
            </head>
            <body>
              <div class="header">
                <h1>მომხმარებლის ინფორმაცია</h1>
              </div>
              
              <div class="section">
                <div class="section-title">მომხმარებლის საბაზო მონაცემები</div>
                <div class="info-grid">
                  <div class="info-label">სახელი:</div>
                  <div class="info-value">${this.user.first_name} ${this.user.last_name}</div>
                  
                  <div class="info-label">ID:</div>
                  <div class="info-value">${this.user.id}</div>
                  
                  <div class="info-label">ელ. ფოსტა:</div>
                  <div class="info-value">${this.user.email}</div>
                  
                  ${this.user.phone ? `
                  <div class="info-label">ტელეფონი:</div>
                  <div class="info-value">${this.user.phone}</div>
                  ` : ''}
                  
                  <div class="info-label">როლი:</div>
                  <div class="info-value">${this.getRoleLabel(this.user.role)}</div>
                  
                  ${this.user.doctor_role ? `
                  <div class="info-label">პროფესია:</div>
                  <div class="info-value">${this.user.doctor_role}</div>
                  ` : ''}
                  
                  <div class="info-label">რეგისტრაციის თარიღი:</div>
                  <div class="info-value">${this.formatDate(this.user.created_at)}</div>
                </div>
              </div>
              
              <div class="section">
                <div class="section-title">ვიზიტების ისტორია</div>
                ${this.visits.length > 0 ? `
                  <table>
                    <thead>
                      <tr>
                        <th>თარიღი</th>
                        <th>დრო</th>
                        <th>პაციენტი</th>
                        <th>განყოფილება</th>
                        <th>სტატუსი</th>
                        <th>შენიშვნა</th>
                      </tr>
                    </thead>
                    <tbody>
                      ${this.visits.map(visit => {
                        const visitDate = new Date(visit.date);
                        const formattedDate = visitDate.toLocaleDateString('ka-GE', { 
                          year: 'numeric', 
                          month: '2-digit', 
                          day: '2-digit' 
                        });
                        return `
                          <tr>
                            <td>${formattedDate}</td>
                            <td>${visit.time}</td>
                            <td>${visit.patientName || '-'}</td>
                            <td>${visit.department || '-'}</td>
                            <td>${this.getStatusLabel(visit.status)}</td>
                            <td>${visit.notes || '-'}</td>
                          </tr>
                        `;
                      }).join('')}
                    </tbody>
                  </table>
                ` : `
                  <div class="no-visits">ვიზიტები არ მოიძებნა</div>
                `}
              </div>
              
              <div class="print-date">
                დაბეჭდილია: ${new Date().toLocaleDateString('ka-GE', { 
                  year: 'numeric', 
                  month: 'long', 
                  day: 'numeric',
                  hour: '2-digit',
                  minute: '2-digit'
                })}
              </div>
            </body>
          </html>
      `;
      
      printWindow.document.write(printContent);
      printWindow.document.close();
      
      // Wait for content to load, then print
      setTimeout(() => {
        printWindow.print();
      }, 250);
    }
  }
};
</script>

