<template>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <Navbar />

    <main class="max-w-[1485px] mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Stats -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
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
              <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">გადახდილი</p>
              <p class="text-3xl font-bold text-blue-600 dark:text-blue-400">{{ paidCount }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
              <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">დაუფარავი</p>
              <p class="text-3xl font-bold text-orange-600 dark:text-orange-400">
                ₾{{ pendingAmount.toFixed(2) }}
              </p>
            </div>
            <div class="w-12 h-12 bg-orange-100 dark:bg-orange-900 rounded-lg flex items-center justify-center">
              <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
              </svg>
            </div>
          </div>
        </div>
      </div>

      <!-- Table -->
      <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6">
        <div class="mb-6 flex flex-col gap-3 xl:flex-row xl:flex-wrap xl:items-end xl:justify-between">
          <div class="min-w-0 shrink-0 xl:max-w-[280px]">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white leading-tight">
              გადახდების ისტორია
            </h2>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-0.5">
              ყველა გადახდის სრული ჩანაწერი
            </p>
          </div>

          <div class="flex flex-1 flex-wrap items-end gap-3 min-w-0">
            <div class="w-full min-[480px]:w-auto min-[480px]:min-w-[140px]">
              <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">პერიოდი</label>
              <select
                v-model="filters.datePreset"
                @change="onDatePresetChange"
                class="block w-full py-2 px-3 border rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
              >
                <option value="today">დღეს</option>
                <option value="yesterday">გუშინ</option>
                <option value="week">1 კვირა</option>
                <option value="month">1 თვე</option>
                <option value="custom">Custom Date</option>
              </select>
            </div>

            <template v-if="filters.datePreset === 'custom'">
              <div class="w-full min-[480px]:w-auto min-[480px]:min-w-[150px]">
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">თარიღიდან</label>
                <input
                  v-model="filters.dateFrom"
                  type="date"
                  @input="onCustomDateChange"
                  @change="applyFilters"
                  class="block w-full py-2 px-3 border rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                />
              </div>
              <div class="w-full min-[480px]:w-auto min-[480px]:min-w-[150px]">
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">თარიღამდე</label>
                <input
                  v-model="filters.dateTo"
                  type="date"
                  @input="onCustomDateChange"
                  @change="applyFilters"
                  class="block w-full py-2 px-3 border rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                />
              </div>
            </template>

            <div class="w-full min-[480px]:w-auto min-[480px]:min-w-[140px]">
              <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">მეთოდი</label>
              <select
                v-model="filters.paymentMethod"
                @change="applyFilters"
                class="block w-full py-2 px-3 border rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
              >
                <option value="">ყველა</option>
                <option value="cash">ნაღდი</option>
                <option value="card">ბარათი</option>
                <option value="transfer">გადარიცხვა</option>
              </select>
            </div>

            <div class="w-full min-[480px]:w-auto min-[480px]:min-w-[160px]">
              <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">განყოფილება</label>
              <select
                v-model="filters.department"
                @change="applyFilters"
                class="block w-full py-2 px-3 border rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
              >
                <option value="">ყველა</option>
                <option v-for="dep in uniqueDepartments" :key="dep" :value="dep">
                  {{ dep }}
                </option>
              </select>
            </div>

            <div class="w-full min-[480px]:w-auto min-[480px]:min-w-[180px]">
              <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">ექიმი</label>
              <select
                v-model="filters.doctor"
                @change="applyFilters"
                class="block w-full py-2 px-3 border rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
              >
                <option value="">ყველა</option>
                <option v-for="doc in uniqueDoctors" :key="doc" :value="doc">
                  {{ doc }}
                </option>
              </select>
            </div>
          </div>

          <div class="flex flex-wrap items-center gap-2 shrink-0">
            <Button variant="secondary" @click="exportToExcel" class="hidden md:inline-flex">
              <svg class="w-5 h-5 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
              </svg>
              Excel-ში ექსპორტი
            </Button>
            <Button variant="primary" @click="printFilteredPayments">
              <svg class="w-5 h-5 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
              </svg>
              დაბეჭდვა
            </Button>
          </div>
        </div>

        <!-- Mobile Add Button -->
        <div class="md:hidden mb-4 space-y-2">
          <Button variant="secondary" @click="exportToExcel" class="w-full">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            Excel-ში ექსპორტი
          </Button>
          <Button variant="primary" @click="printFilteredPayments" class="w-full">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
            </svg>
            დაბეჭდვა (ფილტრი)
          </Button>
        </div>

          <Table
            :data="filteredPayments"
            :columns="columns"
            :page-size="10"
            :searchable="true"
            :wrap-cells="true"
            search-placeholder="მოძებნეთ გადახდა (ინვოისი, პაციენტი, სერვისი)..."
            empty-message="გადახდები არ მოიძებნა"
            :loading="loading"
          />
      </div>

      <Modal :isOpen="isEditModalOpen" title="გადახდის რედაქტირება" @close="closeEditModal">
        <form @submit.prevent="handleEditSubmit" class="space-y-4">
          <div class="p-3 bg-gray-50 dark:bg-gray-700/40 border border-gray-200 dark:border-gray-600 rounded-lg">
            <p class="text-sm text-gray-600 dark:text-gray-300">პაციენტი</p>
            <p class="text-sm font-semibold text-gray-900 dark:text-white mt-1">{{ editFormData.patientName || '—' }}</p>
          </div>

          <div v-if="editFormData.department" class="p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
            <p class="text-sm text-blue-800 dark:text-blue-200">
              განყოფილება: <span class="font-semibold">{{ editFormData.department }}</span>
            </p>
          </div>

          <ServiceSearch
            v-model="editFormData.services"
            :services="services"
            :department="editFormData.department"
            placeholder="მოძებნეთ სერვისები..."
          />

          <Input
            v-model="editFormData.amount"
            type="number"
            step="0.01"
            min="0"
            label="ჯამური თანხა (₾)"
            placeholder="0.00"
            required
          />

          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              გადახდის მეთოდი
            </label>
            <select
              v-model="editFormData.payment_method"
              class="block w-full py-3 px-3 border rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
              required
            >
              <option value="cash">ნაღდი</option>
              <option value="card">ბარათი</option>
              <option value="transfer">გადარიცხვა</option>
            </select>
          </div>

          <div v-if="editError" class="p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
            <p class="text-sm text-red-800 dark:text-red-300">{{ editError }}</p>
          </div>

          <div class="flex gap-3 pt-4">
            <Button type="button" variant="secondary" @click="closeEditModal" :full-width="true">
              გაუქმება
            </Button>
            <Button type="submit" variant="primary" :full-width="true" :disabled="editSubmitting">
              {{ editSubmitting ? 'იტვირთება...' : 'შენახვა' }}
            </Button>
          </div>
        </form>
      </Modal>

      <!-- Add Payment Modal -->
      <Modal :isOpen="isModalOpen" title="ახალი გადახდის დამატება" @close="closeModal">
        <form @submit.prevent="handleSubmit" class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              პაციენტი
            </label>
            <select
              v-model="formData.patient_id"
              class="block w-full py-3 px-3 border rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
              required
            >
              <option value="" v-if="patientsLoading">იტვირთება...</option>
              <option value="" v-else>აირჩიეთ პაციენტი</option>
              <option v-for="patient in patients" :key="patient.id" :value="patient.id">
                {{ patient.fullName }} {{ patient.last_name }}
              </option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              ექიმი *
            </label>
            <select
              v-model="formData.doctor_id"
              class="block w-full py-3 px-3 border rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
              required
            >
              <option value="">აირჩიეთ ექიმი</option>
              <option v-for="user in doctorUsers" :key="user.id" :value="user.id">
                {{ user.first_name }} {{ user.last_name }} {{ user.doctor_role ? `(${user.doctor_role})` : '' }}
              </option>
            </select>
          </div>

          <Input
            v-model="formData.service"
            label="სერვისი"
            placeholder="მაგ: რუტინული შემოწმება, კონსულტაცია..."
            required
          />

          <div class="grid grid-cols-2 gap-4">
            <Input
              v-model="formData.amount"
              type="number"
              step="0.01"
              min="0"
              label="თანხა (₾)"
              placeholder="0.00"
              required
            />
            
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                გადახდის თარიღი
              </label>
              <input
                v-model="formData.payment_date"
                type="date"
                class="block w-full py-3 px-3 border rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                required
              />
            </div>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              გადახდის მეთოდი
            </label>
            <select
              v-model="formData.payment_method"
              class="block w-full py-3 px-3 border rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
              required
            >
              <option value="cash">ნაღდი</option>
              <option value="card">ბარათი</option>
              <option value="transfer">გადარიცხვა</option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              სტატუსი
            </label>
            <select
              v-model="formData.status"
              class="block w-full py-3 px-3 border rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
            >
              <option value="pending">მოლოდინში</option>
              <option value="paid">გადახდილი</option>
              <option value="cancelled">გაუქმებული</option>
            </select>
          </div>

          <div v-if="error" class="p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
            <p class="text-sm text-red-800 dark:text-red-300">{{ error }}</p>
          </div>

          <div class="flex gap-3 pt-4">
            <Button type="button" variant="secondary" @click="closeModal" :full-width="true">
              გაუქმება
            </Button>
            <Button type="submit" variant="primary" :full-width="true" :disabled="submitting">
              {{ submitting ? 'იტვირთება...' : 'დამატება' }}
            </Button>
          </div>
        </form>
      </Modal>
    </main>
  </div>
</template>

<script>
import axios from 'axios';
import { useToastStore } from '../stores/toast';
import Navbar from './Navbar.vue';
import Table from './ui/Table.vue';
import Button from './ui/Button.vue';
import Modal from './ui/Modal.vue';
import Input from './ui/Input.vue';
import ServiceSearch from './ServiceSearch.vue';

export default {
  name: 'Payments',
  components: {
    Navbar,
    Table,
    Button,
    Modal,
    Input,
    ServiceSearch
  },
  setup() {
    const toastStore = useToastStore();
    return { toastStore };
  },
  data() {
    const now = new Date();
    const today = `${now.getFullYear()}-${String(now.getMonth() + 1).padStart(2, '0')}-${String(now.getDate()).padStart(2, '0')}`;

    return {
      payments: [],
      allPayments: [],
      patients: [],
      patientsLoading: false,
      doctorUsers: [],
      services: [],
      pdfFiles: [],
      loading: true,
      isModalOpen: false,
      isEditModalOpen: false,
      submitting: false,
      editSubmitting: false,
      error: '',
      editError: '',
      editingPaymentId: null,
      filters: {
        datePreset: 'today',
        dateFrom: today,
        dateTo: today,
        paymentMethod: '',
        department: '',
        doctor: ''
      },
      formData: {
        patient_id: '',
        doctor_id: '',
        service: '',
        amount: '',
        payment_date: '',
        payment_method: 'cash',
        status: 'pending'
      },
      editFormData: {
        patientName: '',
        department: '',
        services: [],
        amount: '',
        payment_method: 'cash'
      },
      columns: [
        {
          key: 'invoiceNumber',
          label: 'ინვოისი',
          sortable: true,
          filterable: true,
          width: '100px',
          render: (value) => `<span class="font-mono font-semibold text-blue-600 dark:text-blue-400 text-sm">${value}</span>`
        },
        {
          key: 'patientName',
          label: 'პაციენტი',
          sortable: true,
          filterable: true,
          width: '140px',
          render: (value, item) => {
            const idNumber = item.patientIdNumber ? `<div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">პ/ნ: ${item.patientIdNumber}</div>` : '';
            return `<div><div class="font-medium text-gray-900 dark:text-white">${value}</div>${idNumber}</div>`;
          }
        },
        {
          key: 'doctor',
          label: 'ექიმი',
          sortable: true,
          filterable: true,
          width: '120px',
          render: (value) => value ? `<span class="text-sm font-medium text-blue-600 dark:text-blue-400">${value}</span>` : `<span class="text-sm text-gray-400 dark:text-gray-500">-</span>`
        },
        {
          key: 'department',
          label: 'განყოფილება',
          sortable: true,
          filterable: true,
          width: '120px',
          render: (value) => value ? `<span class="text-sm text-gray-800 dark:text-gray-200">${value}</span>` : `<span class="text-sm text-gray-400 dark:text-gray-500">-</span>`
        },
        {
          key: 'service',
          label: 'სერვისი',
          sortable: true,
          filterable: true,
          width: '150px',
          render: (value, item) => {
            let html = `<div class="text-sm text-gray-600 dark:text-gray-400">`;
            
            // If there are services with discounts, show them separately
            if (item.servicesDiscounts && Array.isArray(item.servicesDiscounts) && item.servicesDiscounts.length > 0) {
              item.servicesDiscounts.forEach((service, index) => {
                const serviceName = service.name || '';
                const servicePrice = parseFloat(service.price) || 0;
                const discount = parseFloat(service.discount) || 0;
                const discountedPrice = servicePrice * (1 - discount / 100);
                
                html += `<div class="mb-1 ${index > 0 ? 'mt-1.5 pt-1.5 border-t border-gray-200 dark:border-gray-700' : ''}">`;
                html += `<div class="font-medium text-xs">${serviceName}</div>`;
                html += `<div class="text-xs text-gray-500 dark:text-gray-500 mt-0.5">`;
                if (discount > 0) {
                  html += `<span class="line-through text-gray-400">₾${servicePrice.toFixed(2)}</span> `;
                  html += `<span class="text-green-600 dark:text-green-400 font-semibold">₾${discountedPrice.toFixed(2)}</span> `;
                  html += `<span class="text-orange-600 dark:text-orange-400">(-${discount.toFixed(2)}%)</span>`;
                } else {
                  html += `<span>₾${servicePrice.toFixed(2)}</span>`;
                }
                html += `</div></div>`;
              });
            } else {
              // Fallback to regular service display
              html += `<span>${value}</span>`;
            }
            
            html += `</div>`;
            return html;
          }
        },
        {
          key: 'amount',
          label: 'თანხა',
          sortable: true,
          width: '90px',
          render: (value) => `<span class="font-bold text-green-600 dark:text-green-400 text-sm">₾${Number(value).toFixed(2)}</span>`
        },
        {
          key: 'paymentMethod',
          label: 'მეთოდი / სტატუსი',
          sortable: true,
          filterable: true,
          width: '120px',
          render: (value, item) => {
            const methods = {
              cash: { label: 'ნაღდი', class: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' },
              card: { label: 'ბარათი', class: 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200' },
              transfer: { label: 'გადარიცხვა', class: 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' }
            };
            const method = methods[value];
            const methodHtml = method ? `<div><span class="px-2 py-0.5 rounded-full text-xs font-medium ${method.class}">${method.label}</span></div>` : '<div>-</div>';
            
            const statuses = {
              paid: { label: 'გადახდილი', class: 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' },
              pending: { label: 'მოლოდინში', class: 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200' },
              cancelled: { label: 'გაუქმებული', class: 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }
            };
            const status = statuses[item.status];
            const statusHtml = status ? `<div class="mt-1"><span class="px-2 py-0.5 rounded-full text-xs font-medium ${status.class}">${status.label}</span></div>` : '';
            
            return methodHtml + statusHtml;
          }
        },
        {
          key: 'date',
          label: 'თარიღი',
          sortable: true,
          width: '120px',
          render: (value, item) => {
            const dateText = typeof value === 'string' ? value.slice(0, 10) : '';
            const dateSource = /^\d{4}-\d{2}-\d{2}$/.test(dateText) ? `${dateText}T00:00:00` : '';
            const dateObj = dateSource ? new Date(dateSource) : null;
            const dateStr = dateObj && !Number.isNaN(dateObj.getTime())
              ? dateObj.toLocaleDateString('ka-GE', { year: 'numeric', month: 'short', day: 'numeric' })
              : '-';

            const createdRaw = typeof item?.created_at === 'string' ? item.created_at.replace(' ', 'T') : '';
            const createdDate = createdRaw ? new Date(createdRaw) : null;
            const timeStr = createdDate && !Number.isNaN(createdDate.getTime())
              ? createdDate.toLocaleTimeString('ka-GE', { hour: '2-digit', minute: '2-digit' })
              : '-';
            return `<div><div class="text-xs text-gray-600 dark:text-gray-400">${dateStr}</div><div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">${timeStr}</div></div>`;
          }
        },
        {
          key: 'actions',
          label: 'მოქმედებები',
          sortable: false,
          width: '150px',
          render: (value, item) => {
            return `
              <div class="flex flex-wrap justify-center gap-1.5" onclick="event.stopPropagation()">
                <button
                  onclick="window.editPayment(${item.id}); return false;"
                  class="px-2.5 py-1 text-xs sm:text-sm bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg transition cursor-pointer inline-flex items-center gap-1"
                  title="რედაქტირება"
                >
                  <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                  </svg>
                  რედაქტირება
                </button>
                <button
                  onclick="window.printPayment(${item.id}); return false;"
                  class="px-2.5 py-1 text-xs sm:text-sm bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition cursor-pointer inline-flex items-center gap-1"
                  title="დაბეჭდვა"
                >
                  <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                  </svg>
                  დაბეჭდვა
                </button>
                <button
                  onclick="window.deletePayment(${item.id}); return false;"
                  class="px-2.5 py-1 text-xs sm:text-sm bg-red-600 hover:bg-red-700 text-white rounded-lg transition cursor-pointer inline-flex items-center gap-1"
                  title="წაშლა"
                >
                  <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                  </svg>
                  წაშლა
                </button>
              </div>
            `;
          }
        }
      ]
    };
  },
  computed: {
    filteredPayments() {
      let filtered = [...this.allPayments];
      const dateFrom = this.filters.dateFrom;
      const dateTo = this.filters.dateTo;

      let startDate = dateFrom;
      let endDate = dateTo;
      if (startDate && endDate && startDate > endDate) {
        startDate = endDate;
        endDate = dateFrom;
      }

      if (startDate) {
        filtered = filtered.filter(p => {
          const paymentDate = this.extractPaymentDate(p.date);
          return paymentDate && paymentDate >= startDate;
        });
      }

      if (endDate) {
        filtered = filtered.filter(p => {
          const paymentDate = this.extractPaymentDate(p.date);
          return paymentDate && paymentDate <= endDate;
        });
      }

      // Filter by payment method
      if (this.filters.paymentMethod) {
        filtered = filtered.filter(p => {
          return p.paymentMethod === this.filters.paymentMethod;
        });
      }

      if (this.filters.department) {
        filtered = filtered.filter(p => (p.department || '') === this.filters.department);
      }

      if (this.filters.doctor) {
        filtered = filtered.filter(p => (p.doctor || '') === this.filters.doctor);
      }

      return filtered;
    },
    uniqueDepartments() {
      const set = new Set();
      (this.allPayments || []).forEach((p) => {
        const d = p.department != null ? String(p.department).trim() : '';
        if (d) set.add(d);
      });
      return [...set].sort((a, b) => a.localeCompare(b, 'ka'));
    },
    uniqueDoctors() {
      const set = new Set();
      (this.allPayments || []).forEach((p) => {
        const d = p.doctor != null ? String(p.doctor).trim() : '';
        if (d) set.add(d);
      });
      return [...set].sort((a, b) => a.localeCompare(b, 'ka'));
    },
    totalRevenue() {
      return this.filteredPayments.filter(p => p.status === 'paid').reduce((sum, p) => sum + Number(p.amount), 0);
    },
    pendingAmount() {
      return this.filteredPayments.filter(p => p.status === 'pending').reduce((sum, p) => sum + Number(p.amount), 0);
    },
    paidCount() {
      return this.filteredPayments.filter(p => p.status === 'paid').length;
    },
    pendingCount() {
      return this.filteredPayments.filter(p => p.status === 'pending').length;
    }
  },
  mounted() {
    this.fetchPayments();
    this.fetchPatients();
    this.fetchDoctors();
    this.fetchServices();
    this.fetchPdfFiles();
    const now = new Date();
    this.formData.payment_date = `${now.getFullYear()}-${String(now.getMonth() + 1).padStart(2, '0')}-${String(now.getDate()).padStart(2, '0')}`;
    // Make print method available globally for table actions
    window.printPayment = (id) => this.printPayment(id);
    window.editPayment = (id) => this.openEditModal(id);
    window.deletePayment = (id) => this.deletePayment(id);
  },
  beforeUnmount() {
    delete window.printPayment;
    delete window.editPayment;
    delete window.deletePayment;
  },
  methods: {
    parseDateTimeForDisplay(value) {
      if (!value) return null;
      const raw = String(value).trim().replace(' ', 'T');
      const parsed = new Date(raw);
      if (Number.isNaN(parsed.getTime())) return null;
      return parsed;
    },
    getPaymentDateTimeValue(payment) {
      if (payment?.created_at) return payment.created_at;
      return payment?.date || '';
    },
    formatDate(value) {
      return `${value.getFullYear()}-${String(value.getMonth() + 1).padStart(2, '0')}-${String(value.getDate()).padStart(2, '0')}`;
    },
    getRangeFromPreset(preset) {
      const now = new Date();
      const today = this.formatDate(now);
      if (preset === 'today') {
        return { dateFrom: today, dateTo: today };
      }
      if (preset === 'yesterday') {
        const yesterdayDate = new Date(now);
        yesterdayDate.setDate(yesterdayDate.getDate() - 1);
        const yesterday = this.formatDate(yesterdayDate);
        return { dateFrom: yesterday, dateTo: yesterday };
      }
      if (preset === 'week') {
        const weekStartDate = new Date(now);
        weekStartDate.setDate(weekStartDate.getDate() - 6);
        return { dateFrom: this.formatDate(weekStartDate), dateTo: today };
      }
      if (preset === 'month') {
        const monthStartDate = new Date(now);
        monthStartDate.setDate(monthStartDate.getDate() - 29);
        return { dateFrom: this.formatDate(monthStartDate), dateTo: today };
      }
      return { dateFrom: this.filters.dateFrom, dateTo: this.filters.dateTo };
    },
    onDatePresetChange() {
      if (this.filters.datePreset === 'custom') {
        if (!this.filters.dateFrom || !this.filters.dateTo) {
          const today = this.formatDate(new Date());
          this.filters.dateFrom = today;
          this.filters.dateTo = today;
        }
        this.applyFilters();
        return;
      }

      const range = this.getRangeFromPreset(this.filters.datePreset);
      this.filters.dateFrom = range.dateFrom;
      this.filters.dateTo = range.dateTo;
      this.applyFilters();
    },
    onCustomDateChange() {
      if (this.filters.datePreset !== 'custom') {
        this.filters.datePreset = 'custom';
      }
      this.applyFilters();
    },
    extractPaymentDate(value) {
      if (!value) return '';
      const raw = String(value);
      const match = raw.match(/^(\d{4}-\d{2}-\d{2})/);
      if (match) return match[1];

      const parsed = new Date(value);
      if (Number.isNaN(parsed.getTime())) return '';
      const y = parsed.getFullYear();
      const m = String(parsed.getMonth() + 1).padStart(2, '0');
      const d = String(parsed.getDate()).padStart(2, '0');
      return `${y}-${m}-${d}`;
    },
    async fetchPatients() {
      this.patientsLoading = true;
      try {
        const token = localStorage.getItem('auth_token');
        const response = await axios.get('/api/patients', {
          headers: { 'Authorization': `Bearer ${token}` }
        });
        this.patients = response.data;
      } catch (error) {
        console.error('Failed to fetch patients:', error);
        this.patients = [];
      } finally {
        this.patientsLoading = false;
      }
    },
    async fetchDoctors() {
      try {
        const token = localStorage.getItem('auth_token');
        const response = await axios.get('/api/users', {
          headers: { 'Authorization': `Bearer ${token}` }
        });
        
        // Filter users with DOCTOR role
        this.doctorUsers = response.data.filter(user => user.role === 'DOCTOR')
          .sort((a, b) => {
            const nameA = `${a.last_name} ${a.first_name}`;
            const nameB = `${b.last_name} ${b.first_name}`;
            return nameA.localeCompare(nameB);
          });
      } catch (error) {
        console.error('Failed to fetch doctors:', error);
        this.doctorUsers = [];
      }
    },
    async fetchServices() {
      try {
        const token = localStorage.getItem('auth_token');
        const response = await axios.get('/api/services', {
          headers: { 'Authorization': `Bearer ${token}` }
        });
        this.services = Array.isArray(response.data) ? response.data : [];
      } catch (error) {
        console.error('Failed to fetch services:', error);
        this.services = [];
      }
    },
    async fetchPdfFiles() {
      try {
        const token = localStorage.getItem('auth_token');
        const response = await axios.get('/api/pdf', {
          headers: { 'Authorization': `Bearer ${token}` }
        });
        this.pdfFiles = response.data || [];
      } catch (error) {
        console.error('Failed to fetch PDF files:', error);
        this.pdfFiles = [];
      }
    },
    normalizeString(str) {
      if (!str) return '';
      return str
        .toLowerCase()
        .replace(/\s+/g, '')
        .replace(/[^\w\u10A0-\u10FF]/g, '')
        .trim();
    },
    findMatchingPdfFile(serviceName) {
      if (!serviceName || !this.pdfFiles || this.pdfFiles.length === 0) return null;
      
      // Extract meaningful words from service name (remove numbers, common words)
      const serviceWords = this.extractWords(serviceName);
      if (serviceWords.length === 0) return null;
      
      let bestMatch = null;
      let bestMatchScore = 0;
      
      for (const file of this.pdfFiles) {
        const fileNameWords = this.extractWords(file.basename);
        if (fileNameWords.length === 0) continue;
        
        // Count matching words
        let matchCount = 0;
        for (const serviceWord of serviceWords) {
          for (const fileNameWord of fileNameWords) {
            if (serviceWord === fileNameWord || 
                serviceWord.includes(fileNameWord) || 
                fileNameWord.includes(serviceWord)) {
              matchCount++;
              break;
            }
          }
        }
        
        // Calculate match score (percentage of matching words)
        const matchScore = matchCount / Math.max(serviceWords.length, fileNameWords.length);
        
        // If at least 50% of words match, consider it a match
        if (matchScore >= 0.5 && matchScore > bestMatchScore) {
          bestMatch = file;
          bestMatchScore = matchScore;
        }
      }
      
      return bestMatch;
    },
    extractWords(str) {
      if (!str) return [];
      
      // Remove numbers, special characters, and split into words
      const words = str
        .toLowerCase()
        .replace(/^[0-9\s-]+/g, '') // Remove leading numbers, dashes, and spaces
        .replace(/[^\w\u10A0-\u10FF\s]/g, ' ') // Replace special chars with space
        .split(/\s+/)
        .filter(word => word.length > 2) // Filter out very short words
        .filter(word => {
          // Filter out only very common filler words, but keep meaningful medical terms
          const commonWords = ['ახალი', 'ძველი'];
          return !commonWords.includes(word);
        });
      
      return words;
    },
    openPdfFile(file) {
      if (!file) return;
      
      const url = `/pdf/${file.name}`;
      const extension = file.extension?.toLowerCase() || file.name.split('.').pop()?.toLowerCase();
      
      if (extension === 'pdf') {
        // For PDF files, open in iframe and trigger print dialog
        const iframe = document.createElement('iframe');
        iframe.style.display = 'none';
        iframe.src = url;
        document.body.appendChild(iframe);
        
        iframe.onload = () => {
          setTimeout(() => {
            try {
              iframe.contentWindow.print();
            } catch (e) {
              // If print fails, open in new window
              const printWindow = window.open(url, '_blank');
              if (printWindow) {
                printWindow.onload = () => {
                  setTimeout(() => {
                    printWindow.print();
                  }, 500);
                };
              }
            }
            // Remove iframe after printing
            setTimeout(() => {
              document.body.removeChild(iframe);
            }, 1000);
          }, 500);
        };
      } else if (extension === 'docx' || extension === 'doc') {
        // For DOCX files, download the file
        const link = document.createElement('a');
        link.href = url;
        link.download = file.name;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        
        // Show info message
        this.toastStore.info('DOCX ფაილი ჩამოიტვირთა. გთხოვთ გახსნათ და დაბეჭდოთ.');
      } else {
        // Fallback: open in new tab
        window.open(url, '_blank');
      }
    },
    async fetchPayments() {
      this.loading = true;
      try {
        const token = localStorage.getItem('auth_token');
        const response = await axios.get('/api/payments', {
          headers: { 'Authorization': `Bearer ${token}` }
        });
        this.allPayments = response.data;
        this.payments = this.allPayments;
      } catch (error) {
        console.error('Failed to fetch payments:', error);
        this.error = error.response?.data?.message || 'გადახდების ჩატვირთვა ვერ მოხერხდა';
        this.allPayments = [];
        this.payments = [];
      } finally {
        this.loading = false;
      }
    },
    applyFilters() {
      // Filters are applied automatically via computed property
    },
    printFilteredPayments() {
      const list = this.filteredPayments || [];
      if (list.length === 0) {
        this.toastStore.warning('ბეჭდვისთვის გაფილტრული გადახდები არ არის');
        return;
      }
      this.printMultiplePayments([...list]);
    },
    printMultiplePayments(payments) {
      if (payments.length === 0) return;

      const printWindow = window.open('', '_blank');
      if (!printWindow) return;

      const firstPayment = payments[0];
      const allSamePatient =
        payments.length > 0 &&
        payments.every(p => p.patientId === payments[0].patientId);
      let totalAmount = 0;
      payments.forEach(p => totalAmount += parseFloat(p.amount) || 0);

      let paymentsHtml = '';
      payments.forEach((payment, index) => {
        const paymentDate = this.parseDateTimeForDisplay(this.getPaymentDateTimeValue(payment));
        const formattedDate = paymentDate ? paymentDate.toLocaleDateString('ka-GE', {
          year: 'numeric', 
          month: 'long', 
          day: 'numeric',
          hour: '2-digit',
          minute: '2-digit'
        }) : '-';

        paymentsHtml += `
          <div style="page-break-after: ${index < payments.length - 1 ? 'always' : 'auto'}; margin-bottom: 40px;">
            <div class="header">
              <h1>გადახდის ინვოისი</h1>
              <p style="margin-top: 10px; font-size: 18px; color: #666;">ინვოისი #${payment.invoiceNumber}</p>
            </div>
            
            <div class="invoice-info">
              <div class="info-section">
                <h3>პაციენტი</h3>
                <p style="font-weight: 600; margin-bottom: 5px;">${payment.patientName || '-'}</p>
                ${payment.patientIdNumber ? `<p style="font-size: 14px; color: #666; margin: 3px 0;">პ/ნ: ${payment.patientIdNumber}</p>` : ''}
                ${payment.patientDateOfBirth ? `<p style="font-size: 14px; color: #666; margin: 3px 0;">დაბადების თარიღი: ${new Date(payment.patientDateOfBirth).toLocaleDateString('ka-GE', { year: 'numeric', month: 'long', day: 'numeric' })}</p>` : ''}
              </div>
              <div class="info-section" style="text-align: right;">
                <h3>გადახდის თარიღი</h3>
                <p>${formattedDate}</p>
              </div>
            </div>

            <div class="invoice-details">
              <div class="detail-row">
                <div class="detail-label">ექიმი:</div>
                <div class="detail-value">${payment.doctor || '-'}</div>
              </div>
              <div class="detail-row">
                <div class="detail-label">სერვისები:</div>
                <div class="detail-value">
                  ${this.formatServicesForPrint(payment)}
                </div>
              </div>
              <div class="detail-row">
                <div class="detail-label">გადახდის მეთოდი:</div>
                <div class="detail-value">${this.getPaymentMethodLabel(payment.paymentMethod)}</div>
              </div>
              <div class="detail-row">
                <div class="detail-label">სტატუსი:</div>
                <div class="detail-value">${this.getStatusLabel(payment.status)}</div>
              </div>
            </div>

            <div class="amount-section">
              <div class="amount-label">გადასახდელი თანხა</div>
              <div class="amount-value">₾${Number(payment.amount).toFixed(2)}</div>
            </div>

            <div class="footer">
              <p>დაბეჭდილია: ${new Date().toLocaleDateString('ka-GE', { 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
              })}</p>
            </div>
          </div>
        `;
      });

      // Add summary page if multiple payments
      let summaryHtml = '';
      if (payments.length > 1) {
        summaryHtml = `
          <div style="page-break-before: always; padding-top: 40px;">
            <div class="header">
              <h1>გადახდების შეჯამება</h1>
            </div>
            
            <div class="invoice-info">
              <div class="info-section">
                <h3>პაციენტი</h3>
                ${
                  allSamePatient
                    ? `<p style="font-weight: 600; margin-bottom: 5px;">${firstPayment.patientName || '-'}</p>
                ${firstPayment.patientIdNumber ? `<p style="font-size: 14px; color: #666; margin: 3px 0;">პ/ნ: ${firstPayment.patientIdNumber}</p>` : ''}`
                    : `<p style="font-weight: 600; margin-bottom: 5px;">სხვადასხვა პაციენტის გადახდები</p>`
                }
              </div>
            </div>

            <div class="invoice-details">
              <div class="detail-row">
                <div class="detail-label">სულ გადახდები:</div>
                <div class="detail-value">${payments.length}</div>
              </div>
              ${payments.map((p, idx) => `
                <div class="detail-row">
                  <div class="detail-label">${idx + 1}. ინვოისი ${p.invoiceNumber}:</div>
                  <div class="detail-value">₾${Number(p.amount).toFixed(2)}</div>
                </div>
              `).join('')}
            </div>

            <div class="amount-section">
              <div class="amount-label">მთლიანი თანხა</div>
              <div class="amount-value">₾${totalAmount.toFixed(2)}</div>
            </div>

            <div class="footer">
              <p>დაბეჭდილია: ${new Date().toLocaleDateString('ka-GE', { 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
              })}</p>
            </div>
          </div>
        `;
      }

      const printContent = `
        <!DOCTYPE html>
        <html>
          <head>
            <title>გადახდების ინვოისები${allSamePatient ? ` - ${firstPayment.patientName || ''}` : ''}</title>
            <style>
              body {
                font-family: Arial, sans-serif;
                padding: 40px;
                color: #333;
                max-width: 800px;
                margin: 0 auto;
              }
              .header {
                text-align: center;
                margin-bottom: 40px;
                border-bottom: 3px solid #333;
                padding-bottom: 20px;
              }
              .header h1 {
                margin: 0;
                font-size: 28px;
                font-weight: bold;
              }
              .invoice-info {
                display: flex;
                justify-content: space-between;
                margin-bottom: 30px;
              }
              .info-section {
                flex: 1;
              }
              .info-section h3 {
                margin: 0 0 10px 0;
                font-size: 14px;
                color: #666;
                text-transform: uppercase;
              }
              .info-section p {
                margin: 5px 0;
                font-size: 16px;
                font-weight: 500;
              }
              .invoice-details {
                margin: 30px 0;
                border: 2px solid #333;
                border-radius: 8px;
                overflow: hidden;
              }
              .detail-row {
                display: flex;
                border-bottom: 1px solid #ddd;
                padding: 15px;
              }
              .detail-row:last-child {
                border-bottom: none;
              }
              .detail-label {
                font-weight: bold;
                width: 200px;
                color: #666;
              }
              .detail-value {
                flex: 1;
                font-size: 16px;
              }
              .amount-section {
                background-color: #f5f5f5;
                padding: 20px;
                border-radius: 8px;
                margin-top: 30px;
                text-align: center;
              }
              .amount-label {
                font-size: 18px;
                color: #666;
                margin-bottom: 10px;
              }
              .amount-value {
                font-size: 36px;
                font-weight: bold;
                color: #22c55e;
              }
              .footer {
                margin-top: 40px;
                padding-top: 20px;
                border-top: 1px solid #ddd;
                text-align: center;
                font-size: 12px;
                color: #666;
              }
              @media print {
                body {
                  padding: 20px;
                }
              }
            </style>
          </head>
          <body>
            ${paymentsHtml}
            ${summaryHtml}
          </body>
        </html>
      `;
      
      printWindow.document.write(printContent);
      printWindow.document.close();
      
      setTimeout(() => {
        printWindow.print();
      }, 250);
    },
    exportToExcel() {
      try {
        // Export filtered payments to Excel
        if (!this.filteredPayments || this.filteredPayments.length === 0) {
          this.toastStore.warning('ექსპორტირებისთვის მონაცემები არ არის');
          return;
        }

        const dataToExport = this.filteredPayments.map(payment => {
          const date = this.parseDateTimeForDisplay(this.getPaymentDateTimeValue(payment));
          
          // Format services with discounts
          let servicesText = payment.service || '-';
          if (payment.servicesDiscounts && Array.isArray(payment.servicesDiscounts) && payment.servicesDiscounts.length > 0) {
            servicesText = payment.servicesDiscounts.map(service => {
              const serviceName = service.name || '';
              const servicePrice = parseFloat(service.price) || 0;
              const discount = parseFloat(service.discount) || 0;
              const discountedPrice = servicePrice * (1 - discount / 100);
              
              if (discount > 0) {
                return `${serviceName}: ₾${servicePrice.toFixed(2)} → ₾${discountedPrice.toFixed(2)} (-${discount.toFixed(2)}%)`;
              } else {
                return `${serviceName}: ₾${servicePrice.toFixed(2)}`;
              }
            }).join(' | ');
          }
          
          return {
            'ინვოისი': payment.invoiceNumber || '',
            'პაციენტი': payment.patientName || '',
            'განყოფილება': payment.department || '-',
            'ექიმი': payment.doctor || '-',
            'სერვისები': servicesText,
            'თანხა': Number(payment.amount || 0).toFixed(2),
            'ფასდაკლება': payment.hasDiscount ? `${(payment.discountPercentage || 0).toFixed(2)}%` : '-',
            'გადახდის მეთოდი': this.getPaymentMethodLabel(payment.paymentMethod),
            'სტატუსი': this.getStatusLabel(payment.status),
            'თარიღი': date ? date.toLocaleDateString('ka-GE', { year: 'numeric', month: '2-digit', day: '2-digit' }) : '-',
            'დრო': date ? date.toLocaleTimeString('ka-GE', { hour: '2-digit', minute: '2-digit' }) : '-'
          };
        });

        // Get headers
        const headers = Object.keys(dataToExport[0]);
        
        // Create CSV rows
        const csvRows = [
          headers.join(','),
          ...dataToExport.map(row => 
            headers.map(header => {
              const value = row[header] || '';
              // Escape commas and quotes
              return `"${String(value).replace(/"/g, '""')}"`;
            }).join(',')
          )
        ];

        // Create CSV string
        const csvContent = csvRows.join('\n');
        
        // Add BOM for UTF-8 Excel compatibility
        const BOM = '\uFEFF';
        const blob = new Blob([BOM + csvContent], { type: 'text/csv;charset=utf-8;' });
        
        // Create download link
        const link = document.createElement('a');
        const url = URL.createObjectURL(blob);
        link.setAttribute('href', url);
        
        // Generate filename with date range
        const now = new Date();
        const dateStr = now.toISOString().split('T')[0];
        let filename = `payments_${dateStr}.csv`;
        
        if (this.filters.dateFrom && this.filters.dateTo) {
          filename = `payments_${this.filters.dateFrom}_${this.filters.dateTo}.csv`;
        }
        
        link.setAttribute('download', filename);
        link.style.visibility = 'hidden';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        
        // Clean up URL
        setTimeout(() => URL.revokeObjectURL(url), 100);
        
        this.toastStore.success('Excel ფაილი წარმატებით ჩამოიტვირთა');
      } catch (error) {
        console.error('Excel export error:', error);
        this.toastStore.error('ექსპორტირებისას მოხდა შეცდომა');
      }
    },
    getPaymentMethodLabel(method) {
      const methods = {
        cash: 'ნაღდი',
        card: 'ბარათი',
        transfer: 'გადარიცხვა'
      };
      return methods[method] || method;
    },
    getStatusLabel(status) {
      const statuses = {
        paid: 'გადახდილი',
        pending: 'მოლოდინში',
        cancelled: 'გაუქმებული'
      };
      return statuses[status] || status;
    },
    formatServicesForPrint(payment) {
      // If there are services with discounts, show them separately
      if (payment.servicesDiscounts && Array.isArray(payment.servicesDiscounts) && payment.servicesDiscounts.length > 0) {
        let html = '<div style="display: flex; flex-direction: column; gap: 12px;">';
        
        payment.servicesDiscounts.forEach((service, index) => {
          const serviceName = service.name || '';
          const servicePrice = parseFloat(service.price) || 0;
          const discount = parseFloat(service.discount) || 0;
          const discountedPrice = servicePrice * (1 - discount / 100);
          
          html += '<div style="padding: 10px; background-color: #f9fafb; border-radius: 6px; border-left: 3px solid #3b82f6;">';
          html += `<div style="font-weight: 600; font-size: 15px; margin-bottom: 6px;">${serviceName}</div>`;
          html += '<div style="font-size: 13px; color: #666;">';
          
          if (discount > 0) {
            html += `<span style="text-decoration: line-through; color: #999; margin-right: 8px;">₾${servicePrice.toFixed(2)}</span>`;
            html += `<span style="color: #22c55e; font-weight: 600; margin-right: 8px;">₾${discountedPrice.toFixed(2)}</span>`;
            html += `<span style="color: #f97316; font-weight: 500;">(ფასდაკლება: ${discount.toFixed(2)}%)</span>`;
          } else {
            html += `<span style="font-weight: 500;">₾${servicePrice.toFixed(2)}</span>`;
          }
          
          html += '</div></div>';
        });
        
        html += '</div>';
        return html;
      } else {
        // Fallback to regular service display
        return `<span>${payment.service || '-'}</span>`;
      }
    },
    openModal() {
      this.isModalOpen = true;
      this.error = '';
      const today = new Date().toISOString().split('T')[0];
      this.formData = {
        patient_id: '',
        doctor_id: '',
        service: '',
        amount: '',
        payment_date: today,
        payment_method: 'cash',
        status: 'pending'
      };
      if (!this.patients || this.patients.length === 0) {
        this.fetchPatients();
      }
      if (!this.doctorUsers || this.doctorUsers.length === 0) {
        this.fetchDoctors();
      }
    },
    closeModal() {
      this.isModalOpen = false;
      this.error = '';
    },
    buildEditServices(payment) {
      const namesFromDiscounts = Array.isArray(payment?.servicesDiscounts)
        ? payment.servicesDiscounts
          .map((service) => String(service?.name || '').trim())
          .filter(Boolean)
        : [];
      const names = namesFromDiscounts.length > 0
        ? namesFromDiscounts
        : String(payment?.service || '')
          .split(',')
          .map((name) => name.trim())
          .filter(Boolean);

      if (names.length === 0) {
        return [];
      }

      return names.map((name, index) => {
        const found = this.services.find((service) => String(service?.name || '').trim().toLowerCase() === name.toLowerCase());
        if (found) {
          return found;
        }
        return {
          id: `custom-${index}-${name}`,
          name,
          price: 0,
          department: payment?.department || ''
        };
      });
    },
    parseAmount(value) {
      if (value === null || value === undefined || value === '') {
        return NaN;
      }
      const normalized = String(value).trim().replace(/\s/g, '').replace(',', '.');
      const amount = Number.parseFloat(normalized);
      return Number.isFinite(amount) ? amount : NaN;
    },
    async openEditModal(paymentId) {
      const payment = this.allPayments.find(p => p.id === paymentId);
      if (!payment) {
        this.toastStore.warning('გადახდა ვერ მოიძებნა');
        return;
      }

      if (!this.services.length) {
        await this.fetchServices();
      }

      this.editingPaymentId = payment.id;
      this.editFormData = {
        patientName: payment.patientName || '',
        department: payment.department || '',
        services: this.buildEditServices(payment),
        amount: payment.amount != null ? String(payment.amount) : '',
        payment_method: payment.paymentMethod || 'cash'
      };
      this.editError = '';
      this.isEditModalOpen = true;
    },
    closeEditModal() {
      this.isEditModalOpen = false;
      this.editError = '';
      this.editSubmitting = false;
      this.editingPaymentId = null;
    },
    async handleEditSubmit() {
      if (this.editSubmitting || this.editingPaymentId == null) {
        return;
      }

      if (!this.editFormData.payment_method || this.editFormData.amount === '') {
        this.editError = 'გთხოვთ შეავსოთ ყველა აუცილებელი ველი';
        return;
      }

      const selectedServices = Array.isArray(this.editFormData.services) ? this.editFormData.services : [];
      if (selectedServices.length === 0) {
        this.editError = 'გთხოვთ აირჩიოთ მინიმუმ ერთი სერვისი';
        return;
      }

      const serviceNames = selectedServices
        .map((service) => String(service?.name || '').trim())
        .filter(Boolean);
      if (serviceNames.length === 0) {
        this.editError = 'გთხოვთ აირჩიოთ მინიმუმ ერთი სერვისი';
        return;
      }

      const amount = this.parseAmount(this.editFormData.amount);
      if (!Number.isFinite(amount) || amount < 0) {
        this.editError = 'თანხა არ შეიძლება იყოს უარყოფითი';
        return;
      }

      this.editSubmitting = true;
      this.editError = '';

      try {
        const token = localStorage.getItem('auth_token');
        await axios.patch(`/api/payments/${this.editingPaymentId}`, {
          service: serviceNames.join(', '),
          services: selectedServices.map((service) => ({
            name: service.name,
            price: service.price ?? 0,
            discount: 0
          })),
          amount,
          payment_method: this.editFormData.payment_method
        }, {
          headers: { Authorization: `Bearer ${token}` }
        });

        this.toastStore.success('გადახდა განახლდა');
        await this.fetchPayments();
        this.closeEditModal();
      } catch (error) {
        this.editError = error.response?.data?.message || 'გადახდის განახლება ვერ მოხერხდა';
      } finally {
        this.editSubmitting = false;
      }
    },
    async handleSubmit() {
      if (this.submitting) {
        return;
      }
      this.submitting = true;
      this.error = '';

      try {
        // Get doctor name from selected doctor_id
        const selectedDoctor = this.doctorUsers.find(d => d.id === Number(this.formData.doctor_id));
        const doctorName = selectedDoctor ? `${selectedDoctor.first_name} ${selectedDoctor.last_name}` : null;
        
        const paymentData = {
          ...this.formData,
          user_id: this.formData.doctor_id || null,
          doctor: doctorName
        };
        
        const token = localStorage.getItem('auth_token');
        await axios.post('/api/payments', paymentData, {
          headers: { 'Authorization': `Bearer ${token}` }
        });

        // Refresh payments list
        await this.fetchPayments();
        
        // Close modal
        this.closeModal();
      } catch (error) {
        this.error = error.response?.data?.message || 'შეცდომა მოხდა გადახდის დამატებისას';
      } finally {
        this.submitting = false;
      }
    },
    async deletePayment(paymentId) {
      if (!confirm('დარწმუნებული ხართ, რომ გსურთ ამ გადახდის წაშლა?')) {
        return;
      }
      try {
        const token = localStorage.getItem('auth_token');
        await axios.delete(`/api/payments/${paymentId}`, {
          headers: { Authorization: `Bearer ${token}` }
        });
        this.toastStore.success('გადახდა წაიშალა');
        await this.fetchPayments();
      } catch (error) {
        const msg = error.response?.data?.message || 'გადახდის წაშლა ვერ მოხერხდა';
        this.toastStore.error(msg);
      }
    },
    printPayment(paymentId) {
      const payment = this.allPayments.find(p => p.id === paymentId || p.invoiceNumber === paymentId);
      if (!payment) {
        this.toastStore.warning('გადახდა არ მოიძებნა');
        return;
      }

      // Check for matching PDF files for services
      const servicesToCheck = [];
      if (payment.servicesDiscounts && Array.isArray(payment.servicesDiscounts) && payment.servicesDiscounts.length > 0) {
        payment.servicesDiscounts.forEach(service => {
          if (service.name) {
            servicesToCheck.push(service.name);
          }
        });
      } else if (payment.service) {
        const services = Array.isArray(payment.service) ? payment.service : [payment.service];
        servicesToCheck.push(...services);
      }

      // Open matching PDF files
      const openedFiles = [];
      servicesToCheck.forEach(serviceName => {
        const matchingFile = this.findMatchingPdfFile(serviceName);
        if (matchingFile && !openedFiles.find(f => f.name === matchingFile.name)) {
          this.openPdfFile(matchingFile);
          openedFiles.push(matchingFile);
        }
      });

      const printWindow = window.open('', '_blank');
      if (!printWindow) return;

      const paymentDate = this.parseDateTimeForDisplay(this.getPaymentDateTimeValue(payment));
      const formattedDate = paymentDate ? paymentDate.toLocaleDateString('ka-GE', {
        year: 'numeric', 
        month: 'long', 
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
      }) : '-';

      const printContent = `
        <!DOCTYPE html>
        <html>
          <head>
            <title>გადახდის ინვოისი - ${payment.invoiceNumber}</title>
            <style>
              body {
                font-family: Arial, sans-serif;
                padding: 40px;
                color: #333;
                max-width: 800px;
                margin: 0 auto;
              }
              .header {
                text-align: center;
                margin-bottom: 40px;
                border-bottom: 3px solid #333;
                padding-bottom: 20px;
              }
              .header h1 {
                margin: 0;
                font-size: 28px;
                font-weight: bold;
              }
              .invoice-info {
                display: flex;
                justify-content: space-between;
                margin-bottom: 30px;
              }
              .info-section {
                flex: 1;
              }
              .info-section h3 {
                margin: 0 0 10px 0;
                font-size: 14px;
                color: #666;
                text-transform: uppercase;
              }
              .info-section p {
                margin: 5px 0;
                font-size: 16px;
                font-weight: 500;
              }
              .invoice-details {
                margin: 30px 0;
                border: 2px solid #333;
                border-radius: 8px;
                overflow: hidden;
              }
              .detail-row {
                display: flex;
                border-bottom: 1px solid #ddd;
                padding: 15px;
              }
              .detail-row:last-child {
                border-bottom: none;
              }
              .detail-label {
                font-weight: bold;
                width: 200px;
                color: #666;
              }
              .detail-value {
                flex: 1;
                font-size: 16px;
              }
              .amount-section {
                background-color: #f5f5f5;
                padding: 20px;
                border-radius: 8px;
                margin-top: 30px;
                text-align: center;
              }
              .amount-label {
                font-size: 18px;
                color: #666;
                margin-bottom: 10px;
              }
              .amount-value {
                font-size: 36px;
                font-weight: bold;
                color: #22c55e;
              }
              .footer {
                margin-top: 40px;
                padding-top: 20px;
                border-top: 1px solid #ddd;
                text-align: center;
                font-size: 12px;
                color: #666;
              }
              @media print {
                body {
                  padding: 20px;
                }
              }
            </style>
          </head>
          <body>
            <div class="header">
              <h1>გადახდის ინვოისი</h1>
              <p style="margin-top: 10px; font-size: 18px; color: #666;">ინვოისი #${payment.invoiceNumber}</p>
            </div>
            
            <div class="invoice-info">
              <div class="info-section">
                <h3>პაციენტი</h3>
                <p style="font-weight: 600; margin-bottom: 5px;">${payment.patientName || '-'}</p>
                ${payment.patientIdNumber ? `<p style="font-size: 14px; color: #666; margin: 3px 0;">პ/ნ: ${payment.patientIdNumber}</p>` : ''}
                ${payment.patientDateOfBirth ? `<p style="font-size: 14px; color: #666; margin: 3px 0;">დაბადების თარიღი: ${new Date(payment.patientDateOfBirth).toLocaleDateString('ka-GE', { year: 'numeric', month: 'long', day: 'numeric' })}</p>` : ''}
              </div>
              <div class="info-section" style="text-align: right;">
                <h3>გადახდის თარიღი</h3>
                <p>${formattedDate}</p>
              </div>
            </div>

            <div class="invoice-details">
              <div class="detail-row">
                <div class="detail-label">ექიმი:</div>
                <div class="detail-value">${payment.doctor || '-'}</div>
              </div>
              <div class="detail-row">
                <div class="detail-label">სერვისები:</div>
                <div class="detail-value">
                  ${this.formatServicesForPrint(payment)}
                </div>
              </div>
              <div class="detail-row">
                <div class="detail-label">გადახდის მეთოდი:</div>
                <div class="detail-value">${this.getPaymentMethodLabel(payment.paymentMethod)}</div>
              </div>
              <div class="detail-row">
                <div class="detail-label">სტატუსი:</div>
                <div class="detail-value">${this.getStatusLabel(payment.status)}</div>
              </div>
            </div>

            <div class="amount-section">
              <div class="amount-label">გადასახდელი თანხა</div>
              <div class="amount-value">₾${Number(payment.amount).toFixed(2)}</div>
            </div>

            <div class="footer">
              <p>დაბეჭდილია: ${new Date().toLocaleDateString('ka-GE', { 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
              })}</p>
            </div>
          </body>
        </html>
      `;
      
      printWindow.document.write(printContent);
      printWindow.document.close();
      
      setTimeout(() => {
        printWindow.print();
      }, 250);
    }
  }
};
</script>

