<template>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <Navbar />

    <main class="max-w-[1485px] mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Stats -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">სულ ვიზიტები</p>
              <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ totalAppointments }}</p>
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
              <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">დადასტურებული</p>
              <p class="text-3xl font-bold text-blue-600 dark:text-blue-400">{{ confirmedCount }}</p>
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
              <p class="text-3xl font-bold text-yellow-600 dark:text-yellow-400">{{ pendingCount }}</p>
            </div>
            <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900 rounded-lg flex items-center justify-center">
              <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
          </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">დღევანდელი</p>
              <p class="text-3xl font-bold text-green-600 dark:text-green-400">{{ todayAppointments }}</p>
            </div>
            <div class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
              <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
          </div>
        </div>
      </div>

      <!-- Table -->
      <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6">
        <div class="mb-6 flex items-center justify-between">
          <div>
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">
              ვიზიტების სია
            </h2>
            <p class="text-gray-600 dark:text-gray-400">
              ყველა დაგეგმილი და დასრულებული ვიზიტი
            </p>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
              როლი: <span class="font-semibold">{{ currentUserRoleLabel }}</span>
            </p>
          </div>
          <Button 
            v-if="currentUserRole !== 'LABOR'"
            variant="primary"
            @click="createNewVisit"
          >
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            ახალი ვიზიტი
          </Button>
        </div>

        <!-- Filters -->
        <div class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              პაციენტი
            </label>
            <select
              v-model="filters.patientId"
              @change="applyFilters"
              class="block w-full py-2 px-3 border rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
              <option value="">ყველა პაციენტი</option>
              <option v-for="patient in patients" :key="patient.id" :value="patient.id">
                {{ patient.first_name }} {{ patient.last_name }}
              </option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              სტატუსი
            </label>
            <select
              v-model="filters.status"
              @change="applyFilters"
              class="block w-full py-2 px-3 border rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
              <option value="">ყველა სტატუსი</option>
              <option value="PENDING">მოლოდინში</option>
              <option value="CONFIRMED">დადასტურებული</option>
              <option value="CANCELLED">გაუქმებული</option>
              <option value="COMPLETED">დასრულებული</option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              თარიღიდან
            </label>
            <input
              v-model="filters.dateFrom"
              type="date"
              @change="applyFilters"
              class="block w-full py-2 px-3 border rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
            />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              თარიღამდე
            </label>
            <input
              v-model="filters.dateTo"
              type="date"
              @change="applyFilters"
              class="block w-full py-2 px-3 border rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
            />
          </div>
        </div>

        <div class="mb-4 flex justify-end">
          <Button variant="secondary" @click="clearFilters">
            ფილტრების გასუფთავება
          </Button>
        </div>

        <Table
          :data="filteredAppointments"
          :columns="columns"
          :page-size="10"
          :searchable="true"
          :wrap-cells="true"
          :compact="true"
          search-placeholder="მოძებნეთ ვიზიტი (პაციენტი, ექიმი, სერვისი, შენიშვნა)..."
          empty-message="ვიზიტები არ მოიძებნა"
          :loading="loading"
        />
      </div>

      <Modal :isOpen="isEditModalOpen" title="ვიზიტის რედაქტირება" @close="closeEditVisitModal">
        <form @submit.prevent="handleVisitEditSubmit" class="space-y-4">
          <div class="p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
            <p class="text-sm text-blue-800 dark:text-blue-200">
              პაციენტი: <span class="font-semibold">{{ editVisitForm.patientName || '—' }}</span>
            </p>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              განყოფილება *
            </label>
            <select
              v-model="editVisitForm.department"
              @change="handleEditDepartmentChange"
              class="block w-full py-3 px-3 border rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
              required
            >
              <option value="">აირჩიეთ განყოფილება</option>
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
              v-model="editVisitForm.doctor_id"
              @change="updateEditDoctorName"
              :disabled="!editVisitForm.department"
              class="block w-full py-3 px-3 border rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:bg-gray-100 disabled:dark:bg-gray-800 disabled:cursor-not-allowed"
              required
            >
              <option value="">{{ editVisitForm.department ? 'აირჩიეთ ექიმი' : 'ჯერ აირჩიეთ განყოფილება' }}</option>
              <option
                v-for="user in editDepartmentDoctors"
                :key="user.id"
                :value="user.id"
              >
                {{ user.first_name }} {{ user.last_name }}
              </option>
            </select>
          </div>

          <ServiceSearch
            v-model="editVisitForm.services"
            :services="services"
            :department="editVisitForm.department"
            :disabled="!editVisitForm.department"
            placeholder="მოძებნეთ სერვისები..."
          />

          <div class="grid grid-cols-2 gap-4">
            <Input
              v-model="editVisitForm.date"
              type="date"
              label="თარიღი *"
              required
            />
            <Input
              v-model="editVisitForm.time"
              type="time"
              label="მიღების საათი *"
              required
            />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              სტატუსი *
            </label>
            <select
              v-model="editVisitForm.status"
              class="block w-full py-3 px-3 border rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
              required
            >
              <option value="PENDING">მოლოდინში</option>
              <option value="CONFIRMED">დადასტურებული</option>
              <option value="CANCELLED">გაუქმებული</option>
              <option value="COMPLETED">დასრულებული</option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              შენიშვნა
            </label>
            <textarea
              v-model="editVisitForm.notes"
              rows="3"
              placeholder="დამატებითი ინფორმაცია..."
              class="block w-full py-3 px-3 border rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
            ></textarea>
          </div>

          <div v-if="editError" class="p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
            <p class="text-sm text-red-800 dark:text-red-300">{{ editError }}</p>
          </div>

          <div class="flex gap-3 pt-4">
            <Button type="button" variant="secondary" @click="closeEditVisitModal" :full-width="true">
              გაუქმება
            </Button>
            <Button type="submit" variant="primary" :full-width="true" :disabled="editSubmitting">
              {{ editSubmitting ? 'ინახება...' : 'შენახვა' }}
            </Button>
          </div>
        </form>
      </Modal>

      <!-- Payment Modal -->
      <PaymentModal
        ref="paymentModalRef"
        :is-open="isPaymentModalOpen"
        :patient="selectedPatient"
        :form-data="paymentFormData"
        @close="closePaymentModal"
        @submit="handlePaymentSubmit"
      />
    </main>
  </div>
</template>

<script>
import axios from 'axios';
import { useAuthStore } from '../stores/auth';
import { useToastStore } from '../stores/toast';
import { paymentService } from '../services/paymentService';
import { getTodayDateString, formatDateToInput } from '../utils/dateUtils';
import { formatGeorgianDate } from '../utils/georgianDate';
import Navbar from './Navbar.vue';
import Table from './ui/Table.vue';
import Button from './ui/Button.vue';
import Modal from './ui/Modal.vue';
import Input from './ui/Input.vue';
import ServiceSearch from './ServiceSearch.vue';
import PaymentModal from './PaymentModal.vue';

export default {
  name: 'Visits',
  components: {
    Navbar,
    Table,
    Button,
    Modal,
    Input,
    ServiceSearch,
    PaymentModal
  },
  setup() {
    const authStore = useAuthStore();
    const toastStore = useToastStore();
    return { authStore, toastStore };
  },
  data() {
    return {
      appointments: [],
      allAppointments: [],
      patients: [],
      loading: true,
      isEditModalOpen: false,
      editSubmitting: false,
      editError: '',
      editingVisitId: null,
      editVisitForm: {
        patientName: '',
        department: '',
        doctor_id: '',
        doctor_name: '',
        services: [],
        date: '',
        time: '',
        status: 'PENDING',
        notes: ''
      },
      isPaymentModalOpen: false,
      selectedPatient: null,
      doctorUsers: [],
      services: [],
      pdfFiles: [],
      paymentFormData: {
        service: '',
        services: [],
        doctor_id: '',
        doctor: '',
        department: '',
        appointment_id: '',
        amount: '',
        payment_date: '',
        payment_method: ''
      },
      filters: {
        patientId: '',
        status: '',
        dateFrom: '',
        dateTo: ''
      },
      paymentCreateInFlight: false,
      columns: [
        {
          key: 'patientName',
          label: 'პაციენტი',
          sortable: true,
          filterable: true,
          width: '200px',
          render: (value, item) => {
            const idNumber = item.patientIdNumber ? `<div class="text-[10px] text-gray-500 dark:text-gray-400">პ/ნ: ${item.patientIdNumber}</div>` : '';
            const phone = item.patientPhone
              ? `<div class="text-[10px] text-gray-500 dark:text-gray-400">ტელ: ${item.patientPhone}</div>`
              : '<div class="text-[10px] text-gray-500 dark:text-gray-400">ტელ: -</div>';
            const ageValue = Number.isFinite(Number(item.patientAge)) ? Math.floor(Math.abs(Number(item.patientAge))) : null;
            const age = ageValue !== null
              ? `<div class="text-[10px] text-gray-500 dark:text-gray-400">ასაკი: ${ageValue} წ.</div>`
              : '<div class="text-[10px] text-gray-500 dark:text-gray-400">ასაკი: -</div>';
            const dob = item.patientDateOfBirth
              ? `<div class="text-[10px] text-gray-500 dark:text-gray-400">დაბ.: ${formatGeorgianDate(item.patientDateOfBirth)}</div>`
              : '';
            return `<div><div class="font-medium text-xs text-gray-900 dark:text-white">${value}</div>${idNumber}${phone}${age}${dob}</div>`;
          }
        },
        {
          key: 'status',
          label: 'სტატუსი',
          sortable: true,
          filterable: true,
          width: '130px',
          render: (value, item) => {
            const statuses = {
              PENDING: { label: 'მოლოდინში', class: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' },
              CONFIRMED: { label: 'დადასტურებული', class: 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' },
              CANCELLED: { label: 'გაუქმებული', class: 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' },
              COMPLETED: { label: 'დასრულებული', class: 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' },
              pending: { label: 'მოლოდინში', class: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' },
              confirmed: { label: 'დადასტურებული', class: 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' },
              cancelled: { label: 'გაუქმებული', class: 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' },
              completed: { label: 'დასრულებული', class: 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' }
            };
            const status = statuses[value] || { label: value, class: 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200' };
            const visitId = item.id;
            const currentStatus = value?.toUpperCase() || value || 'PENDING';
            const currentUserRole = String(window.vm?.currentUserRole || '').toUpperCase();
            const canEdit = currentUserRole === 'DOCTOR' || currentUserRole === 'LABOR' || currentUserRole === 'ADMIN';

            if (canEdit) {
              return `
                <select
                  data-visit-id="${visitId}"
                  class="visit-status-select px-1.5 py-0.5 rounded-md text-[10px] font-medium bg-white dark:bg-gray-700 text-gray-900 dark:text-white border border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 cursor-pointer w-full"
                  value="${currentStatus}"
                >
                  <option value="PENDING" ${currentStatus === 'PENDING' ? 'selected' : ''}>მოლოდინში</option>
                  <option value="CONFIRMED" ${currentStatus === 'CONFIRMED' ? 'selected' : ''}>დადასტურებული</option>
                  <option value="CANCELLED" ${currentStatus === 'CANCELLED' ? 'selected' : ''}>გაუქმებული</option>
                  <option value="COMPLETED" ${currentStatus === 'COMPLETED' ? 'selected' : ''}>დასრულებული</option>
                </select>
              `;
            }
            return `<span class="px-1.5 py-0.5 rounded-full text-[10px] font-medium ${status.class}">${status.label}</span>`;
          }
        },
        {
          key: 'doctorName',
          label: 'ექიმი / განყოფ.',
          sortable: true,
          filterable: true,
          width: '160px',
          render: (value, item) => {
            const doctor = value ? `<div class="text-xs font-medium text-gray-900 dark:text-white">${value}</div>` : '<div class="text-xs text-gray-400">-</div>';
            const department = item.department ? `<div class="text-[10px] text-gray-500 dark:text-gray-400">${item.department}</div>` : '';
            return `<div>${doctor}${department}</div>`;
          }
        },
        {
          key: 'service',
          label: 'სერვისი',
          sortable: true,
          filterable: true,
          width: '180px',
          render: (value) => {
            if (!value) return '<span class="text-xs text-gray-600 dark:text-gray-400">-</span>';
            const services = Array.isArray(value) ? value : [value];
            return `<div class="text-[11px] text-gray-600 dark:text-gray-400">${services.map(s => `<div>${s}</div>`).join('')}</div>`;
          }
        },
        {
          key: 'date',
          label: 'თარიღი / დრო',
          sortable: true,
          width: '110px',
          render: (value, item) => {
            const date = new Date(value);
            const dateStr = date.toLocaleDateString('ka-GE', { year: 'numeric', month: 'short', day: 'numeric' });
            const timeStr = item.time || '';
            return `<div><div class="font-medium text-gray-900 dark:text-white text-xs">${dateStr}</div><div class="font-mono text-[10px] text-gray-500 dark:text-gray-400">${timeStr}</div></div>`;
          }
        },
        {
          key: 'status_changed_at',
          label: 'სტ. შეცვლა',
          sortable: true,
          width: '110px',
          render: (value) => {
            if (!value) return '<span class="text-[10px] text-gray-500 dark:text-gray-400">-</span>';
            const date = new Date(value);
            const dateStr = date.toLocaleDateString('ka-GE', { year: 'numeric', month: 'short', day: 'numeric' });
            const timeStr = date.toLocaleTimeString('ka-GE', { hour: '2-digit', minute: '2-digit' });
            return `<div><div class="text-[10px] text-gray-600 dark:text-gray-400">${dateStr}</div><div class="text-[10px] text-gray-500 dark:text-gray-400">${timeStr}</div></div>`;
          }
        },
        {
          key: 'notes',
          label: 'შენიშვნა',
          filterable: true,
          width: '160px',
          render: (value) => `<span class="text-[11px] text-gray-600 dark:text-gray-400 line-clamp-2">${value || '-'}</span>`
        },
        {
          key: 'actions',
          label: 'მოქმედებები',
          sortable: false,
          width: '140px',
          render: (value, item) => {
            const userRole = String(window.vm?.currentUserRole || '').toUpperCase();
            const isAdmin = userRole === 'ADMIN';
            const isDoctor = userRole === 'DOCTOR' || userRole === 'LABOR';
            
            if (!isAdmin && !isDoctor) return '<span class="text-sm text-gray-400">-</span>';
            
            let buttons = '';
            
            if (isDoctor) {
              buttons += `
                <button
                  onclick="window.printVisit(${item.id}); return false;"
                  class="px-2 py-1 text-[10px] bg-blue-500 hover:bg-blue-600 text-white rounded-md transition cursor-pointer flex items-center gap-1"
                  title="დაბეჭდვა"
                >
                  <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                  </svg>
                  ბეჭდვა
                </button>
              `;
            }

            if (isAdmin) {
              buttons += `
                <button
                  onclick="window.vm?.openVisitEditModal(${item.id}); return false;"
                  class="px-2 py-1 text-[10px] bg-indigo-600 hover:bg-indigo-700 text-white rounded-md transition cursor-pointer flex items-center gap-1"
                  title="რედაქტირება"
                >
                  <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                  </svg>
                  რედაქტ.
                </button>
              `;
            }

            if (isAdmin) {
              buttons += `
                <button
                  onclick="window.vm?.openPaymentModal(${item.id})"
                  class="px-2 py-1 text-[10px] bg-green-600 hover:bg-green-700 text-white rounded-md transition cursor-pointer flex items-center gap-1"
                  title="გადახდის დამატება"
                >
                  <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                  </svg>
                  გადახდა
                </button>
              `;
            }
            
            return `
              <div class="flex flex-wrap justify-center gap-1.5" onclick="event.stopPropagation()">
                ${buttons}
              </div>
            `;
          }
        }
      ]
    };
  },
  computed: {
    currentUserRole() {
      return String(this.authStore?.userRole || '').toUpperCase();
    },
    currentUserRoleLabel() {
      const labels = {
        ADMIN: 'ადმინისტრატორი',
        DOCTOR: 'ექიმი',
        LABOR: 'ლაბორანტი'
      };
      return labels[this.currentUserRole] || 'უცნობი';
    },
    filteredAppointments() {
      let filtered = [...this.allAppointments];

      // Filter by logged-in doctor/labor user - show only their visits
      if (this.authStore.user && (this.currentUserRole === 'DOCTOR' || this.currentUserRole === 'LABOR')) {
        filtered = filtered.filter(a => {
          // Match by doctor_id
          if (a.doctor_id) {
            return a.doctor_id == this.authStore.user.id;
          }
          // Fallback: match by doctor name if doctor_id is not available
          if (a.doctorName) {
            const userFullName = `${this.authStore.user.first_name} ${this.authStore.user.last_name}`;
            return a.doctorName === userFullName;
          }
          return false;
        });
      }

      // Filter by patient
      if (this.filters.patientId) {
        filtered = filtered.filter(a => {
          // Try to match by patient_id if available, otherwise by patient name
          if (a.patient_id) {
            return a.patient_id == this.filters.patientId;
          }
          const patient = this.patients.find(p => p.id == this.filters.patientId);
          if (patient) {
            const patientName = `${patient.first_name} ${patient.last_name}`;
            return a.patientName === patientName;
          }
          return false;
        });
      }

      // Filter by status
      if (this.filters.status) {
        filtered = filtered.filter(a => {
          const status = a.status?.toUpperCase();
          return status === this.filters.status.toUpperCase();
        });
      }

      // Filter by date range
      if (this.filters.dateFrom) {
        filtered = filtered.filter(a => {
          const visitDate = formatDateToInput(a.date);
          return visitDate >= this.filters.dateFrom;
        });
      }

      if (this.filters.dateTo) {
        filtered = filtered.filter(a => {
          const visitDate = formatDateToInput(a.date);
          return visitDate <= this.filters.dateTo;
        });
      }

      return filtered;
    },
    totalAppointments() {
      return this.filteredAppointments.length;
    },
    confirmedCount() {
      return this.filteredAppointments.filter(a => {
        const status = a.status?.toUpperCase();
        return status === 'CONFIRMED' || status === 'confirmed';
      }).length;
    },
    pendingCount() {
      return this.filteredAppointments.filter(a => {
        const status = a.status?.toUpperCase();
        return status === 'PENDING' || status === 'pending';
      }).length;
    },
    todayAppointments() {
      const today = getTodayDateString();
      return this.filteredAppointments.filter(a => {
        const visitDate = formatDateToInput(a.date);
        return visitDate === today;
      }).length;
    },
    editDepartmentDoctors() {
      if (!this.editVisitForm.department) {
        return [];
      }
      return this.doctorUsers.filter((user) => user.doctor_role === this.editVisitForm.department);
    }
  },
  mounted() {
    // Load user from storage
    this.authStore.loadFromStorage();
    this.fetchAppointments();
    this.fetchPatients();
    this.fetchPdfFiles();
    
    // Fetch doctors and services for payment modal
    if (this.currentUserRole === 'ADMIN') {
      this.fetchDoctors();
      this.fetchServices();
    }
    
    // Expose component instance to window for dropdown handlers
    window.vm = this;
    // Make print method available globally for table actions
    window.printVisit = (id) => this.printVisit(id);
    
    // Add event delegation for status dropdown changes
    this.$nextTick(() => {
      document.addEventListener('change', (e) => {
        if (e.target.classList.contains('visit-status-select')) {
          const visitId = parseInt(e.target.dataset.visitId);
          const newStatus = e.target.value;
          if (visitId && newStatus) {
            this.updateVisitStatus(visitId, newStatus);
          }
        }
      });
    });
  },
  methods: {
    async fetchPatients() {
      try {
        const token = localStorage.getItem('auth_token');
        const response = await axios.get('/api/patients', {
          headers: { 'Authorization': `Bearer ${token}` }
        });
        this.patients = response.data;
      } catch (error) {
        console.error('Failed to fetch patients:', error);
      }
    },
    async fetchAppointments() {
      this.loading = true;
      try {
        const token = localStorage.getItem('auth_token');
        const response = await axios.get('/api/visits', {
          headers: { 'Authorization': `Bearer ${token}` }
        });
        
        // Keep patient_id for filtering
        this.allAppointments = response.data;
        
        this.appointments = this.allAppointments;
      } catch (error) {
        console.error('Failed to fetch visits:', error);
        this.error = error.response?.data?.message || 'ვიზიტების ჩატვირთვა ვერ მოხერხდა';
        this.allAppointments = [];
        this.appointments = [];
      } finally {
        this.loading = false;
      }
    },
    applyFilters() {
      // Filters are applied automatically via computed property
      // This method can be used for additional logic if needed
    },
    clearFilters() {
      this.filters = {
        patientId: '',
        status: '',
        dateFrom: '',
        dateTo: ''
      };
    },
    createNewVisit() {
      this.$router.push('/patients');
    },
    normalizeVisitDate(value) {
      if (!value) {
        return '';
      }
      const normalized = formatDateToInput(value);
      if (normalized) {
        return normalized;
      }
      const parsed = new Date(value);
      if (Number.isNaN(parsed.getTime())) {
        return '';
      }
      const y = parsed.getFullYear();
      const m = String(parsed.getMonth() + 1).padStart(2, '0');
      const d = String(parsed.getDate()).padStart(2, '0');
      return `${y}-${m}-${d}`;
    },
    normalizeVisitTime(value, dateValue) {
      if (value) {
        return String(value).slice(0, 5);
      }
      if (!dateValue) {
        return '';
      }
      const parsed = new Date(dateValue);
      if (Number.isNaN(parsed.getTime())) {
        return '';
      }
      return `${String(parsed.getHours()).padStart(2, '0')}:${String(parsed.getMinutes()).padStart(2, '0')}`;
    },
    buildEditServices(visit) {
      const serviceNames = Array.isArray(visit?.service)
        ? visit.service
        : (visit?.service ? [visit.service] : []);
      const serviceIds = Array.isArray(visit?.service_id) ? visit.service_id : [];
      const mappedById = serviceIds
        .map((id) => this.services.find((service) => Number(service.id) === Number(id)))
        .filter(Boolean);
      if (mappedById.length) {
        return mappedById;
      }
      return serviceNames
        .map((name) => this.services.find((service) => String(service.name).trim().toLowerCase() === String(name).trim().toLowerCase()))
        .filter(Boolean);
    },
    async openVisitEditModal(visitId) {
      const visit = this.allAppointments.find((item) => item.id === visitId);
      if (!visit) {
        this.toastStore.error('ვიზიტი არ მოიძებნა');
        return;
      }

      if (!this.doctorUsers.length) {
        await this.fetchDoctors();
      }
      if (!this.services.length) {
        await this.fetchServices();
      }

      let selectedDoctorId = visit.doctor_id || '';
      if (!selectedDoctorId && visit.doctorName) {
        const matchedDoctor = this.doctorUsers.find((doctor) => `${doctor.first_name} ${doctor.last_name}`.trim().toLowerCase() === String(visit.doctorName).trim().toLowerCase());
        if (matchedDoctor) {
          selectedDoctorId = matchedDoctor.id;
        }
      }

      this.editingVisitId = visit.id;
      this.editVisitForm = {
        patientName: visit.patientName || '',
        department: visit.department || '',
        doctor_id: selectedDoctorId,
        doctor_name: visit.doctorName || '',
        services: this.buildEditServices(visit),
        date: this.normalizeVisitDate(visit.date),
        time: this.normalizeVisitTime(visit.time, visit.date),
        status: (visit.status || 'PENDING').toUpperCase(),
        notes: visit.notes || ''
      };
      this.editError = '';
      this.isEditModalOpen = true;
    },
    handleEditDepartmentChange() {
      this.editVisitForm.doctor_id = '';
      this.editVisitForm.doctor_name = '';
      this.editVisitForm.services = [];
    },
    updateEditDoctorName() {
      if (!this.editVisitForm.doctor_id) {
        this.editVisitForm.doctor_name = '';
        return;
      }
      const selectedDoctor = this.doctorUsers.find((doctor) => Number(doctor.id) === Number(this.editVisitForm.doctor_id));
      this.editVisitForm.doctor_name = selectedDoctor ? `${selectedDoctor.first_name} ${selectedDoctor.last_name}` : '';
    },
    closeEditVisitModal() {
      this.isEditModalOpen = false;
      this.editSubmitting = false;
      this.editError = '';
      this.editingVisitId = null;
    },
    async handleVisitEditSubmit() {
      if (this.editSubmitting || this.editingVisitId == null) {
        return;
      }

      const selectedServices = Array.isArray(this.editVisitForm.services) ? this.editVisitForm.services : [];
      if (!this.editVisitForm.department || !this.editVisitForm.doctor_id || !this.editVisitForm.date || !this.editVisitForm.time || selectedServices.length === 0) {
        this.editError = 'გთხოვთ შეავსოთ ყველა აუცილებელი ველი';
        return;
      }

      const serviceIds = selectedServices.map((service) => Number(service.id)).filter((id) => Number.isFinite(id));
      if (serviceIds.length !== selectedServices.length) {
        this.editError = 'ვიზიტის რედაქტირებისთვის აირჩიეთ სერვისები ჩამონათვალიდან';
        return;
      }

      const selectedDoctor = this.doctorUsers.find((doctor) => Number(doctor.id) === Number(this.editVisitForm.doctor_id));
      if (!selectedDoctor) {
        this.editError = 'აირჩიეთ ექიმი ან მედპერ.';
        return;
      }

      this.editSubmitting = true;
      this.editError = '';
      try {
        const token = localStorage.getItem('auth_token');
        await axios.patch(`/api/visits/${this.editingVisitId}`, {
          doctor_id: Number(selectedDoctor.id),
          doctor_name: `${selectedDoctor.first_name} ${selectedDoctor.last_name}`,
          department: this.editVisitForm.department,
          service: selectedServices.map((service) => service.name),
          service_id: serviceIds,
          date: this.editVisitForm.date,
          time: this.editVisitForm.time,
          status: this.editVisitForm.status || 'PENDING',
          notes: this.editVisitForm.notes || ''
        }, {
          headers: { 'Authorization': `Bearer ${token}` }
        });

        this.toastStore.success('ვიზიტი წარმატებით განახლდა');
        await this.fetchAppointments();
        this.closeEditVisitModal();
      } catch (error) {
        const apiErrors = error.response?.data?.errors;
        if (apiErrors && typeof apiErrors === 'object') {
          const messages = Object.values(apiErrors).flat().filter(Boolean);
          this.editError = messages.length ? messages.join(' ') : 'ვიზიტის განახლება ვერ მოხერხდა';
        } else {
          this.editError = error.response?.data?.message || 'ვიზიტის განახლება ვერ მოხერხდა';
        }
      } finally {
        this.editSubmitting = false;
      }
    },
    async updateVisitStatus(visitId, newStatus) {
      try {
        const token = localStorage.getItem('auth_token');
        const response = await axios.patch(`/api/visits/${visitId}`, {
          status: newStatus
        }, {
          headers: { 'Authorization': `Bearer ${token}` }
        });

        // Update the visit in the local array
        const visitIndex = this.allAppointments.findIndex(v => v.id === visitId);
        if (visitIndex !== -1) {
          this.allAppointments[visitIndex].status = newStatus;
          this.allAppointments[visitIndex].status_changed_at = response.data.visit?.status_changed_at || new Date().toISOString();
        }

        this.toastStore.success('სტატუსი წარმატებით განახლდა');
        
        // Refresh appointments to get updated data with status_changed_at
        await this.fetchAppointments();
      } catch (error) {
        console.error('Failed to update visit status:', error);
        this.toastStore.error('სტატუსის განახლება ვერ მოხერხდა');
      }
    },
    async fetchDoctors() {
      try {
        const token = localStorage.getItem('auth_token');
        const response = await axios.get('/api/users', {
          headers: { 'Authorization': `Bearer ${token}` }
        });
        this.doctorUsers = response.data.filter(user => user.role === 'DOCTOR' || user.role === 'LABOR');
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
        this.services = response.data;
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
    async openPaymentModal(visitId) {
      const visit = this.allAppointments.find(v => v.id === visitId);
      if (!visit) {
        this.toastStore.error('ვიზიტი არ მოიძებნა');
        return;
      }

      // Find patient
      const patient = this.patients.find(p => {
        if (p.id === visit.patient_id) return true;
        const patientName = `${p.first_name} ${p.last_name}`;
        return patientName === visit.patientName;
      });

      if (!patient) {
        this.toastStore.error('პაციენტი არ მოიძებნა');
        return;
      }

      this.selectedPatient = {
        id: patient.id,
        first_name: patient.first_name || visit.patientName.split(' ')[0],
        last_name: patient.last_name || visit.patientName.split(' ')[1] || ''
      };

      // Fetch doctors and services if not already loaded
      if (this.doctorUsers.length === 0) {
        await this.fetchDoctors();
      }
      if (this.services.length === 0) {
        await this.fetchServices();
      }

      // Find doctor by ID or name
      let doctorId = visit.doctor_id || null;
      if (!doctorId && visit.doctorName) {
        const doctor = this.doctorUsers.find(d => 
          `${d.first_name} ${d.last_name}`.trim().toLowerCase() === visit.doctorName.trim().toLowerCase()
        );
        if (doctor) {
          doctorId = doctor.id;
        }
      }

      // Prepare services data
      let serviceName = '';
      let servicesList = [];
      let servicePrice = 0;

      if (visit.service) {
        const services = Array.isArray(visit.service) ? visit.service : [visit.service];
        serviceName = services.join(', ');
        
        // Get service prices from services array
        if (visit.service_id && Array.isArray(visit.service_id)) {
          servicesList = visit.service_id.map((serviceId, index) => {
            const service = this.services.find(s => s.id === serviceId);
            if (service) {
              servicePrice += parseFloat(service.price) || 0;
              return {
                name: service.name,
                price: service.price,
                discount: 0
              };
            }
            return {
              name: services[index] || '',
              price: 0,
              discount: 0
            };
          });
        } else {
          // Fallback: create services list from names only
          servicesList = services.map(name => ({
            name: name,
            price: 0,
            discount: 0
          }));
        }
      }

      const today = getTodayDateString();
      this.paymentFormData = {
        service: serviceName,
        services: servicesList,
        doctor_id: doctorId,
        doctor: visit.doctorName || '',
        department: visit.department || '',
        appointment_id: visit.id,
        amount: servicePrice.toFixed(2),
        payment_date: today,
        payment_method: ''
      };

      this.isPaymentModalOpen = true;
    },
    closePaymentModal() {
      this.isPaymentModalOpen = false;
      this.selectedPatient = null;
      this.paymentFormData = {
        service: '',
        services: [],
        doctor_id: '',
        doctor: '',
        department: '',
        appointment_id: '',
        amount: '',
        payment_date: '',
        payment_method: ''
      };
    },
    parseAmount(value) {
      if (value === null || value === undefined || value === '') {
        return NaN;
      }
      const normalized = String(value).trim().replace(/\s/g, '').replace(',', '.');
      const n = parseFloat(normalized);
      return Number.isFinite(n) ? n : NaN;
    },
    async handlePaymentSubmit(paymentData) {
      if (this.paymentCreateInFlight) {
        return;
      }
      this.paymentCreateInFlight = true;
      try {
        // If doctor_id is not set, try to find it from doctor name
        if (!paymentData.doctor_id && paymentData.doctor) {
          if (this.doctorUsers.length === 0) {
            await this.fetchDoctors();
          }
          const doctor = this.doctorUsers.find(d => 
            `${d.first_name} ${d.last_name}`.trim().toLowerCase() === paymentData.doctor.trim().toLowerCase()
          );
          if (doctor) {
            paymentData.doctor_id = doctor.id;
          }
        }

        // Get doctor name from selected doctor_id
        const selectedDoctor = this.doctorUsers.find(d => d.id === Number(paymentData.doctor_id));
        const doctorName = selectedDoctor ? `${selectedDoctor.first_name} ${selectedDoctor.last_name}` : paymentData.doctor;

        const amount = this.parseAmount(paymentData.amount);
        if (!Number.isFinite(amount) || amount < 0) {
          this.toastStore.error('თანხა არასწორია');
          return;
        }

        const services = Array.isArray(paymentData.services) ? paymentData.services : null;

        const paymentPayload = {
          patient_id: Number(this.selectedPatient.id),
          user_id: paymentData.doctor_id || null,
          appointment_id: paymentData.appointment_id || null,
          service: paymentData.service,
          doctor: doctorName || null,
          amount,
          payment_date: paymentData.payment_date,
          payment_method: paymentData.payment_method,
          status: 'paid',
          services: services && services.length > 0 ? services : null,
        };

        await paymentService.createPayment(paymentPayload);

        this.closePaymentModal();
        this.toastStore.success('გადახდა წარმატებით შეიქმნა');
      } catch (error) {
        console.error('Failed to create payment:', error);
        const data = error.response?.data;
        let errorMessage =
          data?.message ||
          data?.error ||
          error.message ||
          'შეცდომა მოხდა გადახდის შექმნისას';
        if (data?.errors && typeof data.errors === 'object') {
          const parts = Object.values(data.errors).flat().filter(Boolean);
          if (parts.length) {
            errorMessage = parts.join(' ');
          }
        }
        this.toastStore.error(errorMessage);
      } finally {
        this.paymentCreateInFlight = false;
        this.$refs.paymentModalRef?.resetSubmitting?.();
      }
    },
    printVisit(visitId) {
      const visit = this.allAppointments.find(v => v.id === visitId);
      if (!visit) {
        this.toastStore.warning('ვიზიტი არ მოიძებნა');
        return;
      }

      this.openVisitSummaryPrint(visit);

      const services = Array.isArray(visit.service) ? visit.service : (visit.service ? [visit.service] : []);
      if (services.length === 0) {
        return;
      }

      const openedFiles = [];
      services.forEach((serviceName) => {
        const matchingFile = this.findMatchingPdfFile(serviceName);
        if (matchingFile && !openedFiles.find(f => f.name === matchingFile.name)) {
          this.openPdfFile(matchingFile);
          openedFiles.push(matchingFile);
        }
      });

      if (openedFiles.length === 0) {
        this.toastStore.warning('სერვისების შესაბამისი PDF/DOCX ფაილები არ მოიძებნა');
      }
    },
    openVisitSummaryPrint(visit) {
      const getStatusLabel = (status) => {
        const statuses = {
          PENDING: 'მოლოდინში',
          CONFIRMED: 'დადასტურებული',
          CANCELLED: 'გაუქმებული',
          COMPLETED: 'დასრულებული',
          pending: 'მოლოდინში',
          confirmed: 'დადასტურებული',
          cancelled: 'გაუქმებული',
          completed: 'დასრულებული'
        };
        return statuses[status] || status;
      };

      const services = Array.isArray(visit.service) ? visit.service : (visit.service ? [visit.service] : []);
      const servicesHtml = services.length > 0
        ? services.map((s) => `<div class="service-item">${String(s).replace(/</g, '&lt;')}</div>`).join('')
        : '<div class="service-item">-</div>';

      const formattedDate = formatGeorgianDate(visit.date);
      const visitDateObj = visit.date ? new Date(visit.date) : null;
      const formattedTime = visit.time || (visitDateObj && !Number.isNaN(visitDateObj.getTime())
        ? visitDateObj.toLocaleTimeString('ka-GE', { hour: '2-digit', minute: '2-digit' })
        : '—');

      const computeAge = (dobValue, fallback) => {
        if (dobValue) {
          const dob = new Date(dobValue);
          if (!Number.isNaN(dob.getTime())) {
            const now = new Date();
            let years = now.getFullYear() - dob.getFullYear();
            const m = now.getMonth() - dob.getMonth();
            if (m < 0 || (m === 0 && now.getDate() < dob.getDate())) {
              years--;
            }
            if (Number.isFinite(years) && years >= 0) {
              return years;
            }
          }
        }
        const fallbackNum = Number(fallback);
        if (Number.isFinite(fallbackNum) && fallbackNum >= 0) {
          return Math.floor(fallbackNum);
        }
        return null;
      };
      const computedAge = computeAge(visit.patientDateOfBirth, visit.patientAge);

      const dobHtml = visit.patientDateOfBirth
        ? `<br><small style="color: #666;">დაბადების თარიღი: ${formatGeorgianDate(visit.patientDateOfBirth)}</small>`
        : '';
      const ageHtml = computedAge !== null
        ? `<br><small style="color: #666;">ასაკი: ${computedAge} წ.</small>`
        : '';
      const phoneHtml = visit.patientPhone
        ? `<br><small style="color: #666;">ტელ: ${String(visit.patientPhone).replace(/</g, '&lt;').replace(/>/g, '&gt;')}</small>`
        : '';

      const statusChangedHtml = visit.status_changed_at
        ? `${formatGeorgianDate(visit.status_changed_at)}, ${new Date(visit.status_changed_at).toLocaleTimeString('ka-GE', { hour: '2-digit', minute: '2-digit' })}`
        : '';

      const printedAt = `${formatGeorgianDate(new Date())}, ${new Date().toLocaleTimeString('ka-GE', { hour: '2-digit', minute: '2-digit' })}`;

      const notesEscaped = visit.notes ? String(visit.notes).replace(/</g, '&lt;').replace(/>/g, '&gt;') : '';

      const printContent = `
        <!DOCTYPE html>
        <html>
          <head>
            <meta charset="utf-8" />
            <title>ვიზიტის ინფორმაცია - ${visit.id}</title>
            <style>
              body { font-family: Arial, sans-serif; padding: 20px; color: #333; max-width: 800px; margin: 0 auto; }
              .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
              .header h1 { margin: 0; font-size: 24px; font-weight: bold; }
              .visit-info { display: flex; justify-content: space-between; margin-bottom: 15px; }
              .info-section { flex: 1; }
              .info-section h3 { margin: 0 0 5px 0; font-size: 13px; color: #666; text-transform: uppercase; }
              .info-section p { margin: 2px 0; font-size: 15px; font-weight: 500; }
              .visit-details { background-color: #f9f9f9; padding: 10px 12px; border-radius: 6px; margin-bottom: 15px; }
              .detail-row { display: flex; margin-bottom: 6px; padding-bottom: 6px; border-bottom: 1px solid #ddd; }
              .detail-row:last-child { border-bottom: none; margin-bottom: 0; padding-bottom: 0; }
              .detail-label { font-weight: bold; width: 150px; color: #666; }
              .detail-value { flex: 1; color: #333; }
              .service-item { padding: 3px 0; border-bottom: 1px solid #eee; }
              .service-item:last-child { border-bottom: none; }
              .status-badge { display: inline-block; padding: 3px 8px; border-radius: 4px; font-weight: bold; font-size: 13px; }
              .status-pending { background-color: #fef3c7; color: #92400e; }
              .status-confirmed { background-color: #dbeafe; color: #1e40af; }
              .status-cancelled { background-color: #fee2e2; color: #991b1b; }
              .status-completed { background-color: #d1fae5; color: #065f46; }
              .footer { text-align: center; margin-top: 20px; padding-top: 10px; border-top: 1px solid #ddd; color: #666; font-size: 11px; }
              @media print { body { padding: 10px; } }
            </style>
          </head>
          <body>
            <div class="header"><h1>ვიზიტის ინფორმაცია</h1></div>
            <div class="visit-info">
              <div class="info-section"><h3>ვიზიტის ID</h3><p>#${visit.id}</p></div>
              <div class="info-section"><h3>სტატუსი</h3><p>
                <span class="status-badge status-${(visit.status || 'PENDING').toLowerCase()}">${getStatusLabel(visit.status)}</span>
              </p></div>
            </div>
            <div class="visit-details">
              <div class="detail-row">
                <div class="detail-label">პაციენტი:</div>
                <div class="detail-value">
                  ${String(visit.patientName || '—').replace(/</g, '&lt;').replace(/>/g, '&gt;')}
                  ${visit.patientIdNumber ? `<br><small style="color: #666;">პ/ნ: ${String(visit.patientIdNumber).replace(/</g, '&lt;').replace(/>/g, '&gt;')}</small>` : ''}
                  ${phoneHtml}
                  ${dobHtml}
                  ${ageHtml}
                </div>
              </div>
              <div class="detail-row"><div class="detail-label">ექიმი:</div><div class="detail-value">${String(visit.doctorName || '—').replace(/</g, '&lt;').replace(/>/g, '&gt;')}</div></div>
              ${visit.department ? `<div class="detail-row"><div class="detail-label">განყოფილება:</div><div class="detail-value">${String(visit.department).replace(/</g, '&lt;')}</div></div>` : ''}
              <div class="detail-row"><div class="detail-label">თარიღი:</div><div class="detail-value">${formattedDate}</div></div>
              <div class="detail-row"><div class="detail-label">დრო:</div><div class="detail-value">${formattedTime}</div></div>
              <div class="detail-row"><div class="detail-label">სერვისები:</div><div class="detail-value">${servicesHtml}</div></div>
              ${visit.notes ? `<div class="detail-row"><div class="detail-label">შენიშვნა:</div><div class="detail-value">${notesEscaped}</div></div>` : ''}
              ${visit.status_changed_at ? `<div class="detail-row"><div class="detail-label">სტატუსის შეცვლა:</div><div class="detail-value">${statusChangedHtml}</div></div>` : ''}
            </div>
            <div class="footer"><p>დაბეჭდილია: ${printedAt}</p></div>
          </body>
        </html>
      `;

      const printWindow = window.open('', '_blank');
      if (!printWindow) {
        return;
      }
      printWindow.document.write(printContent);
      printWindow.document.close();
      setTimeout(() => {
        printWindow.print();
      }, 250);
    }
  },
  beforeUnmount() {
    // Clean up global method
    delete window.printVisit;
    delete window.vm;
  }
};
</script>
