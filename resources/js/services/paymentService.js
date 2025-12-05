import axios from 'axios';

const getAuthToken = () => localStorage.getItem('auth_token');

const getAuthHeaders = () => ({
  'Authorization': `Bearer ${getAuthToken()}`
});

export const paymentService = {
  async createPayment(paymentData) {
    const response = await axios.post('/api/payments', paymentData, {
      headers: getAuthHeaders()
    });
    return response.data;
  },

  async getPayments() {
    const response = await axios.get('/api/payments', {
      headers: getAuthHeaders()
    });
    return response.data;
  }
};

