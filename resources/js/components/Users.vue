<template>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <Navbar />

    <!-- Main Content -->
    <main class="max-w-[1485px] mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6">
        <div class="mb-6 flex items-center justify-between">
          <div>
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">
              ყველა მომხმარებელი
            </h2>
            <p class="text-gray-600 dark:text-gray-400">
              სისტემაში რეგისტრირებული მომხმარებლების სია
            </p>
          </div>
          <Button variant="primary" @click="openModal" class="hidden md:flex">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            ახალი მომხმარებელი
          </Button>
        </div>

        <!-- Mobile Add Button -->
        <Button variant="primary" @click="openModal" class="md:hidden w-full mb-4">
          <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
          </svg>
          ახალი მომხმარებელი
        </Button>

        <!-- Search Bar -->
        <div class="mb-6">
          <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
            </div>
            <input
              v-model="searchQuery"
              type="text"
              class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500"
              placeholder="მოძებნეთ მომხმარებელი..."
            />
          </div>
        </div>

        <!-- Loading State -->
        <div v-if="loading" class="flex justify-center items-center py-12">
          <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
        </div>

        <!-- Empty State -->
        <div v-else-if="filteredUsers.length === 0" class="text-center py-12">
          <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
          </svg>
          <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">მომხმარებლები არ მოიძებნა</h3>
          <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ searchQuery ? 'სცადეთ სხვა მოძებნა' : 'ჯერ არ არის რეგისტრირებული მომხმარებლები' }}</p>
        </div>

        <!-- Table -->
        <div v-else class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700">
              <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                  ID
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                  სახელი
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                  გვარი
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                  ელ. ფოსტა
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                  ტელეფონი
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                  როლი
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                  რეგისტრაცია
                </th>
              </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
              <tr
                v-for="user in paginatedUsers"
                :key="user.id"
                @click="handleRowClick(user)"
                class="hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition-colors"
              >
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                  {{ user.id }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                  {{ user.first_name }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                  {{ user.last_name }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                  <a :href="`mailto:${user.email}`" class="text-blue-600 hover:text-blue-800 dark:text-blue-400">
                    {{ user.email }}
                  </a>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                  {{ user.phone || '-' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                  <span :class="getRoleBadgeClass(user.role)">
                    {{ getRoleLabel(user.role) }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                  {{ formatDate(user.created_at) }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div v-if="filteredUsers.length > 0" class="flex items-center justify-between border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 px-4 py-3 sm:px-6 mt-4">
          <div class="flex-1 flex justify-between sm:hidden">
            <button
              @click="prevPage"
              :disabled="currentPage === 1"
              class="relative inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              წინა
            </button>
            <button
              @click="nextPage"
              :disabled="currentPage === totalPages"
              class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              შემდეგი
            </button>
          </div>
          <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
              <p class="text-sm text-gray-700 dark:text-gray-300">
                ჩანს
                <span class="font-medium">{{ startIndex + 1 }}</span>
                -
                <span class="font-medium">{{ endIndex }}</span>
                სულ <span class="font-medium">{{ filteredUsers.length }}</span> დან
              </p>
            </div>
            <div>
              <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                <button
                  @click="prevPage"
                  :disabled="currentPage === 1"
                  class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-sm font-medium text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-600 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                  წინა
                </button>
                <button
                  @click="nextPage"
                  :disabled="currentPage === totalPages"
                  class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-sm font-medium text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-600 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                  შემდეგი
                </button>
              </nav>
            </div>
          </div>
        </div>
      </div>

      <!-- Add User Modal -->
      <Modal :isOpen="isModalOpen" title="ახალი მომხმარებლის დამატება" @close="closeModal">
        <form @submit.prevent="handleSubmit" class="space-y-4">
          <div class="grid grid-cols-2 gap-4">
            <Input
              v-model="formData.first_name"
              label="სახელი"
              placeholder="განსაზღვრეთ სახელი"
              required
            />
            <Input
              v-model="formData.last_name"
              label="გვარი"
              placeholder="განსაზღვრეთ გვარი"
              required
            />
          </div>
          
          <Input
            v-model="formData.email"
            type="email"
            label="ელ. ფოსტა"
            placeholder="example@email.com"
            required
          />
          
          <Input
            v-model="formData.phone"
            type="tel"
            label="ტელეფონი"
            placeholder="+995 555 123 456"
          />

          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              როლი
            </label>
            <select
              v-model="formData.role"
              class="block w-full py-3 px-3 border rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
              required
            >
              <option value="PATIENT">პაციენტი</option>
              <option value="DOCTOR">ექიმი</option>
              <option value="ADMIN">ადმინი</option>
            </select>
          </div>

          <PasswordInput
            v-model="formData.password"
            label="პაროლი"
            placeholder="მინიმუმ 8 სიმბოლო"
            required
          />

          <div v-if="error" class="p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
            <p class="text-sm text-red-800 dark:text-red-300">{{ error }}</p>
          </div>

          <div class="flex gap-3 pt-4">
            <Button type="button" variant="secondary" @click="closeModal" :full-width="true">
              გაუქმება
            </Button>
            <Button type="submit" variant="primary" :full-width="true" :disabled="loading">
              {{ loading ? 'იტვირთება...' : 'დამატება' }}
            </Button>
          </div>
        </form>
      </Modal>
    </main>
  </div>
</template>

<script>
import axios from 'axios';
import Navbar from './Navbar.vue';
import Button from './ui/Button.vue';
import Modal from './ui/Modal.vue';
import Input from './ui/Input.vue';
import PasswordInput from './ui/PasswordInput.vue';

export default {
  name: 'Users',
  components: {
    Navbar,
    Button,
    Modal,
    Input,
    PasswordInput
  },
  data() {
    return {
      users: [],
      loading: true,
      searchQuery: '',
      currentPage: 1,
      pageSize: 10,
      isModalOpen: false,
      error: '',
      formData: {
        first_name: '',
        last_name: '',
        email: '',
        phone: '',
        role: 'PATIENT',
        password: ''
      }
    };
  },
  computed: {
    filteredUsers() {
      if (!this.searchQuery) return this.users;
      
      const query = this.searchQuery.toLowerCase();
      return this.users.filter(user =>
        user.first_name?.toLowerCase().includes(query) ||
        user.last_name?.toLowerCase().includes(query) ||
        user.email?.toLowerCase().includes(query) ||
        user.phone?.toLowerCase().includes(query)
      );
    },
    totalPages() {
      return Math.ceil(this.filteredUsers.length / this.pageSize);
    },
    startIndex() {
      return (this.currentPage - 1) * this.pageSize;
    },
    endIndex() {
      return Math.min(this.startIndex + this.pageSize, this.filteredUsers.length);
    },
    paginatedUsers() {
      return this.filteredUsers.slice(this.startIndex, this.endIndex);
    }
  },
  mounted() {
    this.fetchUsers();
  },
  methods: {
    async fetchUsers() {
      this.loading = true;
      try {
        const token = localStorage.getItem('auth_token');
        const response = await axios.get('/api/users', {
          headers: {
            'Authorization': `Bearer ${token}`
          }
        });
        this.users = response.data;
      } catch (error) {
        console.error('Failed to fetch users:', error);
        this.error = error.response?.data?.message || 'მომხმარებლების ჩატვირთვა ვერ მოხერხდა';
        this.users = [];
      } finally {
        this.loading = false;
      }
    },
    handleRowClick(user) {
      console.log('Clicked user:', user);
      // this.$router.push(`/users/${user.id}`);
    },
    getRoleLabel(role) {
      const labels = {
        'PATIENT': 'პაციენტი',
        'DOCTOR': 'ექიმი',
        'ADMIN': 'ადმინი'
      };
      return labels[role] || role;
    },
    getRoleBadgeClass(role) {
      const classes = {
        'PATIENT': 'px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
        'DOCTOR': 'px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
        'ADMIN': 'px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200'
      };
      return classes[role] || 'px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200';
    },
    formatDate(dateString) {
      if (!dateString) return '-';
      const date = new Date(dateString);
      return date.toLocaleDateString('ka-GE');
    },
    nextPage() {
      if (this.currentPage < this.totalPages) {
        this.currentPage++;
      }
    },
    prevPage() {
      if (this.currentPage > 1) {
        this.currentPage--;
      }
    },
    openModal() {
      this.isModalOpen = true;
      this.error = '';
      this.formData = {
        first_name: '',
        last_name: '',
        email: '',
        phone: '',
        role: 'PATIENT',
        password: ''
      };
    },
    closeModal() {
      this.isModalOpen = false;
      this.error = '';
    },
    async handleSubmit() {
      this.loading = true;
      this.error = '';

      try {
        const token = localStorage.getItem('auth_token');
        await axios.post('/api/users', this.formData, {
          headers: {
            'Authorization': `Bearer ${token}`
          }
        });

        // Refresh users list
        await this.fetchUsers();
        
        // Close modal
        this.closeModal();
      } catch (error) {
        this.error = error.response?.data?.message || 'შეცდომა მოხდა მომხმარებლის დამატებისას';
      } finally {
        this.loading = false;
      }
    }
  }
};
</script>

