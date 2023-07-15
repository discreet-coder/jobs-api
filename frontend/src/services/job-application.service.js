import axios from 'axios';
import authHeader from './auth-header';
import { config } from '../config';
const API_URL = config.API_URL;

class JobApplicationService {
  createApplication(data) {
    return axios.post(`${API_URL}/create-job-application`, data);
  }

  allApplications() {
    return axios.get(`${API_URL}/view-job-application`, { headers: authHeader() });
  }

  viewApplication(id) {
    return axios.get(`${API_URL}/view-job-application/${id}`, { headers: authHeader() });
  }

  updateApplication(data) {
    return axios.put(`${API_URL}/update-job-application`, { data, headers: authHeader() });
  }

  deleteApplication(id) {
    return axios.delete(`${API_URL}/delete-job-application/${id}`, { headers: authHeader() });
  }
}

export default new JobApplicationService();
