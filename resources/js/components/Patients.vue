<template>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <Navbar />
    <main class="max-w-[1485px] mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Stats -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">სულ პაციენტები</p>
              <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ patients.length }}</p>
            </div>
          </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
          <div>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">აქტიური</p>
            <p class="text-3xl font-bold text-green-600">{{ activePatients }}</p>
          </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
          <div>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">მამრობითი</p>
            <p class="text-3xl font-bold text-blue-600">{{ malePatients }}</p>
          </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
          <div>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">მდედრობითი</p>
            <p class="text-3xl font-bold text-pink-600">{{ femalePatients }}</p>
          </div>
        </div>
      </div>
      <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">პაციენტების სია</h2>
        <input v-model="searchQuery" type="text" placeholder="მოძებნა..." class="w-full mb-6 px-4 py-2 border rounded-lg" />
        <div v-if="loading" class="text-center py-12">იტვირთება...</div>
        <div v-else class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">სახელი გვარი</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ასაკი</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ტელეფონი</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="patient in filteredPatients" :key="patient.id" @click="handleRowClick(patient)" class="cursor-pointer hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap">{{ patient.id }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ patient.fullName }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ patient.age }} წ.</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ patient.phone }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </main>
  </div>
</template>

<script>
import Navbar from './Navbar.vue';
export default {
  name: 'Patients',
  components: { Navbar },
  data() {
    return {
      patients: [],
      loading: true,
      searchQuery: ''
    };
  },
  computed: {
    filteredPatients() {
      return this.searchQuery ? this.patients.filter(p => p.fullName.toLowerCase().includes(this.searchQuery.toLowerCase())) : this.patients;
    },
    activePatients() { return this.patients.filter(p => p.status === 'active').length; },
    malePatients() { return this.patients.filter(p => p.gender === 'male').length; },
    femalePatients() { return this.patients.filter(p => p.gender === 'female').length; }
  },
  mounted() {
    this.patients = [
      { id: 1, fullName: 'გიორგი მამულაშვილი', age: 35, gender: 'male', phone: '+995 555 123 456', status: 'active' },
      { id: 2, fullName: 'ნინო ბერიძე', age: 28, gender: 'female', phone: '+995 555 234 567', status: 'active' },
      { id: 3, fullName: 'დავით გელაშვილი', age: 45, gender: 'male', phone: '+995 555 345 678', status: 'active' }
    ];
    this.loading = false;
  },
  methods: {
    handleRowClick(patient) {
      console.log('Clicked patient:', patient);
    }
  }
};
</script>
