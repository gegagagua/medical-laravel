<template>
  <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6">
    <div class="flex items-start justify-between mb-6">
      <div>
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
          {{ patient.first_name }} {{ patient.last_name }}
        </h1>
        <div class="flex items-center gap-4 text-sm text-gray-600 dark:text-gray-400">
          <span>ID: {{ patient.id }}</span>
          <span v-if="patient.role" :class="getRoleBadgeClass(patient.role)">
            {{ getRoleLabel(patient.role) }}
          </span>
        </div>
      </div>
      <div class="flex gap-3">
        <Button variant="secondary" @click="$emit('print')">
          <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
          </svg>
          დაბეჭდვა
        </Button>
        <Button variant="primary" @click="$emit('add-visit')">
          <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
          </svg>
          ახალი ვიზიტი
        </Button>
      </div>
    </div>

    <!-- Patient Details Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div>
        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
          ელ. ფოსტა
        </label>
        <p class="text-lg text-gray-900 dark:text-white">
          <a :href="`mailto:${patient.email}`" class="text-blue-600 hover:text-blue-800 dark:text-blue-400">
            {{ patient.email }}
          </a>
        </p>
      </div>

      <div v-if="patient.phone">
        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
          ტელეფონი
        </label>
        <p class="text-lg text-gray-900 dark:text-white">
          <a :href="`tel:${patient.phone}`" class="text-blue-600 hover:text-blue-800 dark:text-blue-400">
            {{ patient.phone }}
          </a>
        </p>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
          რეგისტრაციის თარიღი
        </label>
        <p class="text-lg text-gray-900 dark:text-white">
          {{ formatDate(patient.created_at) }}
        </p>
      </div>
    </div>
  </div>
</template>

<script>
import Button from './ui/Button.vue';

export default {
  name: 'PatientInfoCard',
  components: {
    Button
  },
  props: {
    patient: {
      type: Object,
      required: true
    }
  },
  emits: ['print', 'add-visit'],
  methods: {
    formatDate(date) {
      if (!date) return '-';
      const d = new Date(date);
      return d.toLocaleDateString('ka-GE', { 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric' 
      });
    },
    getRoleLabel(role) {
      const roles = {
        'ADMIN': 'ადმინისტრატორი',
        'DOCTOR': 'ექიმი',
        'LABOR': 'ლაბორატორია',
        'PATIENT': 'პაციენტი'
      };
      return roles[role] || role;
    },
    getRoleBadgeClass(role) {
      const classes = {
        'ADMIN': 'px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200',
        'DOCTOR': 'px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
        'LABOR': 'px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
        'PATIENT': 'px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200'
      };
      return classes[role] || classes['PATIENT'];
    }
  }
};
</script>

