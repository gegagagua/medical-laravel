<template>
  <Modal :isOpen="isOpen" title="ახალი ვიზიტის დამატება" @close="$emit('close')">
    <form @submit.prevent="handleSubmit" class="space-y-4">
      <div v-if="patient" class="p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
        <p class="text-sm font-medium text-blue-900 dark:text-blue-100">
          პაციენტი: {{ patient.first_name }} {{ patient.last_name }}
        </p>
      </div>

      <Input
        v-model="formData.date"
        type="date"
        label="თარიღი"
        required
      />

      <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
          განყოფილება *
        </label>
        <select
          v-model="formData.department"
          class="block w-full py-3 px-3 border rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
          required
        >
          <option value="">აირჩიეთ პროფესია</option>
          <option value="ენდოკრინოლოგია">ენდოკრინოლოგია</option>
          <option value="ბავშვთა და მოზრდილთა ენდოკრინოლოგია">ბავშვთა და მოზრდილთა ენდოკრინოლოგია</option>
          <option value="ექიმი">ექიმი</option>
          <option value="რადიოლოგია">რადიოლოგია</option>
          <option value="ოფთალმოლოგია">ოფთალმოლოგია</option>
          <option value="ნევროლოგია">ნევროლოგია</option>
          <option value="კარდიოლოგია">კარდიოლოგია</option>
          <option value="ტრავმატოლოგია">ტრავმატოლოგია</option>
          <option value="ოტორინოლარინგოლოგია">ოტორინოლარინგოლოგია</option>
          <option value="ლაბორატორია">ლაბორატორია</option>
        </select>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
          ექიმი / მედპერ. *
        </label>
        <select
          v-model="formData.doctor_id"
          class="block w-full py-3 px-3 border rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
          required
          :disabled="!formData.department"
          @change="updateDoctorName"
        >
          <option value="">{{ formData.department ? 'აირჩიეთ ექიმი' : 'ჯერ აირჩიეთ განყოფილება' }}</option>
          <option 
            v-for="user in filteredDoctors" 
            :key="user.id" 
            :value="user.id"
          >
            {{ user.first_name }} {{ user.last_name }}
          </option>
        </select>
      </div>

      <ServiceSearch
        v-model="formData.services"
        :services="services"
        :department="formData.department"
        :disabled="!formData.department"
        placeholder="მოძებნეთ სერვისები..."
      />

      <Input
        v-model="formData.time"
        type="time"
        label="მიღების საათი"
        required
      />

      <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
          შენიშვნა
        </label>
        <textarea
          v-model="formData.notes"
          rows="3"
          placeholder="დამატებითი ინფორმაცია..."
          class="block w-full py-3 px-3 border rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
        ></textarea>
      </div>

      <div v-if="error" class="p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
        <p class="text-sm text-red-800 dark:text-red-300">{{ error }}</p>
      </div>

      <div class="flex gap-3 pt-4">
        <Button type="button" variant="secondary" @click="$emit('close')" :full-width="true">
          გაუქმება
        </Button>
        <Button type="submit" variant="primary" :full-width="true" :disabled="submitting">
          {{ submitting ? 'შექმნა...' : 'ვიზიტის შექმნა' }}
        </Button>
      </div>
    </form>
  </Modal>
</template>

<script>
import Modal from './ui/Modal.vue';
import Button from './ui/Button.vue';
import Input from './ui/Input.vue';
import ServiceSearch from './ServiceSearch.vue';
import { getTodayDateString } from '../utils/dateUtils';

export default {
  name: 'VisitModal',
  components: {
    Modal,
    Button,
    Input,
    ServiceSearch
  },
  props: {
    isOpen: {
      type: Boolean,
      default: false
    },
    patient: {
      type: Object,
      default: null
    },
    laborUsers: {
      type: Array,
      default: () => []
    },
    services: {
      type: Array,
      default: () => []
    }
  },
  emits: ['close', 'submit'],
  data() {
    return {
      formData: {
        date: '',
        department: '',
        doctor: '',
        doctor_id: '',
        services: [],
        service_ids: [],
        time: '',
        notes: ''
      },
      submitting: false,
      error: ''
    };
  },
  computed: {
    filteredDoctors() {
      if (!this.formData.department) {
        return [];
      }
      return this.laborUsers.filter(user => 
        user.doctor_role === this.formData.department
      );
    }
  },
  watch: {
    isOpen(newValue) {
      if (newValue) {
        this.resetForm();
      }
    },
    'formData.department'() {
      this.formData.doctor = '';
      this.formData.doctor_id = '';
      this.formData.services = [];
      this.formData.service_ids = [];
    }
  },
  methods: {
    resetForm() {
      const today = getTodayDateString();
      const now = new Date();
      const defaultTime = `${String(now.getHours()).padStart(2, '0')}:${String(now.getMinutes()).padStart(2, '0')}`;
      this.formData = {
        date: today,
        department: '',
        doctor: '',
        doctor_id: '',
        services: [],
        service_ids: [],
        time: defaultTime,
        notes: ''
      };
      this.error = '';
      this.submitting = false;
    },
    updateDoctorName() {
      if (this.formData.doctor_id) {
        const selectedDoctor = this.filteredDoctors.find(d => d.id === Number(this.formData.doctor_id));
        if (selectedDoctor) {
          this.formData.doctor = `${selectedDoctor.first_name} ${selectedDoctor.last_name}`;
        }
      } else {
        this.formData.doctor = '';
      }
    },
    async handleSubmit() {
      this.submitting = true;
      this.error = '';

      try {
        if (!this.formData.services || this.formData.services.length === 0) {
          this.error = 'გთხოვთ აირჩიოთ მინიმუმ ერთი სერვისი';
          this.submitting = false;
          return;
        }

        // Extract service names and IDs
        const serviceNames = this.formData.services.map(s => s.name);
        const serviceIds = this.formData.services.map(s => s.id);

        this.$emit('submit', { 
          ...this.formData,
          service: serviceNames,
          service_id: serviceIds
        });
      } catch (error) {
        this.error = error.message || 'შეცდომა მოხდა';
        this.submitting = false;
      }
    }
  }
};
</script>

