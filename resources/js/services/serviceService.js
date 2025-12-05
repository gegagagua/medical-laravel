import axios from 'axios';

const getAuthToken = () => localStorage.getItem('auth_token');

const getAuthHeaders = () => ({
  'Authorization': `Bearer ${getAuthToken()}`
});

export const serviceService = {
  async getServices() {
    const response = await axios.get('/api/services', {
      headers: getAuthHeaders()
    });
    return response.data;
  },

  filterByDepartment(services, department) {
    if (!department) return [];
    return services.filter(service => service.department === department);
  },

  searchServices(services, query) {
    if (!query) return services;
    const lowerQuery = query.toLowerCase();
    return services.filter(service => 
      service.name.toLowerCase().includes(lowerQuery) ||
      service.price.toString().includes(lowerQuery)
    );
  }
};

