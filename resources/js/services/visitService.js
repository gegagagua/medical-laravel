import axios from 'axios';

const getAuthToken = () => localStorage.getItem('auth_token');

const getAuthHeaders = () => ({
  'Authorization': `Bearer ${getAuthToken()}`
});

export const visitService = {
  async createVisit(visitData) {
    const response = await axios.post('/api/visits', visitData, {
      headers: getAuthHeaders()
    });
    return response.data;
  },

  async getVisits() {
    const response = await axios.get('/api/visits', {
      headers: getAuthHeaders()
    });
    return response.data;
  },

  async getVisit(visitId) {
    const response = await axios.get(`/api/visits/${visitId}`, {
      headers: getAuthHeaders()
    });
    return response.data;
  },

  async deleteVisit(visitId) {
    const response = await axios.delete(`/api/visits/${visitId}`, {
      headers: getAuthHeaders()
    });
    return response.data;
  }
};

