<template>
  <Modal :isOpen="isOpen" title="გადახდის დამატება" @close="$emit('close')">
    <form @submit.prevent="handleSubmit" class="space-y-4">
      <div v-if="patient" class="p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
        <p class="text-sm font-medium text-blue-900 dark:text-blue-100">
          პაციენტი: {{ patient.first_name }} {{ patient.last_name }}
        </p>
      </div>

      <div v-if="formData.doctor" class="p-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
        <p class="text-sm font-medium text-green-900 dark:text-green-100">
          ექიმი: {{ formData.doctor }}<span v-if="formData.department"> - {{ formData.department }}</span>
        </p>
      </div>

      <!-- Service Info Display (read-only) -->
      <div v-if="formData.service" class="p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
          სერვისები *
        </label>
        <div>
          <div v-if="formData.services && formData.services.length > 0" class="space-y-2">
            <div
              v-for="(service, index) in formData.services"
              :key="index"
              class="flex justify-between items-center py-1 border-b border-blue-200 dark:border-blue-700 last:border-b-0"
            >
              <p class="text-sm font-medium text-blue-900 dark:text-blue-100">
                {{ service.name }}
              </p>
              <p class="text-sm font-semibold text-blue-700 dark:text-blue-300">
                {{ service.price }} ₾
              </p>
            </div>
          </div>
          <p v-else class="text-sm font-medium text-blue-900 dark:text-blue-100">
            {{ formData.service }}
          </p>
          <p v-if="formData.appointment_id" class="text-xs text-blue-700 dark:text-blue-300 mt-2">
            სერვისები ავტომატურად შეივსო ვიზიტიდან
          </p>
        </div>
      </div>
      <div v-else class="p-3 bg-gray-50 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 rounded-lg">
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
          სერვისი *
        </label>
        <p class="text-sm text-gray-500 dark:text-gray-400">
          სერვისი უნდა იყოს არჩეული ვიზიტის შექმნისას
        </p>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
          თანხა *
        </label>
        <input
          v-model="formData.amount"
          type="number"
          placeholder="0.00"
          step="0.01"
          min="0"
          required
          :class="[
            'block w-full py-3 px-3 border rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition',
          ]"
        />
        <p v-if="formData.service" class="mt-1 text-xs text-gray-500 dark:text-gray-400">
          თანხა ავტომატურად შეივსო სერვისიდან
        </p>
      </div>

      <Input
        v-model="formData.payment_date"
        type="date"
        label="გადახდის თარიღი *"
        required
      />

      <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
          გადახდის მეთოდი *
        </label>
        <select
          v-model="formData.payment_method"
          class="block w-full py-3 px-3 border rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
          required
        >
          <option value="">აირჩიეთ მეთოდი</option>
          <option value="cash">ნაღდი</option>
          <option value="card">ბარათი</option>
          <option value="transfer">გადარიცხვა</option>
        </select>
      </div>

      <div v-if="error" class="p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
        <p class="text-sm text-red-800 dark:text-red-300">{{ error }}</p>
      </div>

      <div class="flex gap-3 pt-4">
        <Button type="button" variant="secondary" @click="$emit('close')" :full-width="true">
          გაუქმება
        </Button>
        <Button type="submit" variant="primary" :full-width="true" :disabled="submitting">
          {{ submitting ? 'შექმნა...' : 'გადახდის შექმნა' }}
        </Button>
      </div>
    </form>
  </Modal>
</template>

<script>
import Modal from './ui/Modal.vue';
import Button from './ui/Button.vue';
import Input from './ui/Input.vue';

export default {
  name: 'PaymentModal',
  components: {
    Modal,
    Button,
    Input
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
    formData: {
      type: Object,
      default: () => ({
        service: '',
        services: [], // Array of {name, price} objects
        doctor_id: '',
        doctor: '',
        department: '',
        appointment_id: '',
        amount: '',
        payment_date: '',
        payment_method: ''
      })
    }
  },
  emits: ['close', 'submit'],
  data() {
    return {
      submitting: false,
      error: ''
    };
  },
  watch: {
    isOpen(newValue) {
      if (newValue) {
        this.error = '';
        this.submitting = false;
      }
    }
  },
  methods: {
    handleSubmit() {
      this.submitting = true;
      this.error = '';

      if (!this.formData.service || !this.formData.amount || 
          !this.formData.payment_date || !this.formData.payment_method) {
        this.error = 'გთხოვთ შეავსოთ ყველა აუცილებელი ველი';
        this.submitting = false;
        return;
      }

      this.$emit('submit', { ...this.formData });
    }
  }
};
</script>

