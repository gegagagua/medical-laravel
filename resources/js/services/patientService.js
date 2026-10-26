import axios from 'axios';

const getAuthToken = () => localStorage.getItem('auth_token');

const getAuthHeaders = () => ({
  'Authorization': `Bearer ${getAuthToken()}`,
  Accept: 'application/json',
});

const PATIENTS_CACHE_KEY = 'patients_cache';

const readPatientsCache = () => {
  try {
    const raw = localStorage.getItem(PATIENTS_CACHE_KEY);
    if (!raw) return null;
    const parsed = JSON.parse(raw);
    return Array.isArray(parsed) ? parsed : null;
  } catch (e) {
    return null;
  }
};

const writePatientsCache = (patients) => {
  try {
    localStorage.setItem(PATIENTS_CACHE_KEY, JSON.stringify(patients));
  } catch (e) {
    // Ignore storage quota / serialization errors — cache is best-effort
  }
};

export const clearPatientsCache = () => {
  localStorage.removeItem(PATIENTS_CACHE_KEY);
};

export const patientService = {
  // Synchronously read the cached list (or null if none cached yet)
  getCachedPatients() {
    return readPatientsCache();
  },

  // Return cached patients when available; fetch from the server and cache otherwise.
  // Pass { force: true } to bypass the cache and refresh it from the server.
  async getPatients({ force = false } = {}) {
    if (!force) {
      const cached = readPatientsCache();
      if (cached) return cached;
    }
    const response = await axios.get('/api/patients', {
      headers: getAuthHeaders()
    });
    const list = Array.isArray(response.data) ? response.data : [];
    writePatientsCache(list);
    return list;
  },

  clearCache() {
    clearPatientsCache();
  },

  async getPatient(patientId) {
    const response = await axios.get(`/api/patients/${patientId}`, {
      headers: getAuthHeaders()
    });
    return response.data;
  },

  async getPatientVisits(patientId) {
    const response = await axios.get('/api/visits', {
      headers: getAuthHeaders()
    });
    
    // Filter visits for this patient
    return response.data
      .filter(visit => visit.patient_id == patientId)
      .sort((a, b) => new Date(b.date) - new Date(a.date));
  },

  async getPatientPayments(patientId) {
    const response = await axios.get('/api/payments', {
      headers: getAuthHeaders()
    });

    const list = Array.isArray(response.data) ? response.data : [];
    const pid = Number(patientId);

    return list.filter((payment) => {
      const rowPatientId = Number(payment.patientId ?? payment.patient_id);
      return Number.isFinite(pid) && rowPatientId === pid;
    });
  }
};

