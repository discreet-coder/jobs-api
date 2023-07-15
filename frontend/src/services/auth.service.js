import axios from 'axios';
import { config } from '../config';
const API_URL = config.API_URL;

class AuthService {
  login(user) {
    return axios
      .post(`${API_URL}/login`, {
        email: user.email,
        password: user.password
      })
      .then(response => {
        if (response.data.result.token) {
          localStorage.setItem('token', response.data.result.token);
          localStorage.setItem('user', JSON.stringify(response.data.result.token));
        }
        return response.data;
      });
  }

  logout() {
    const token = localStorage.getItem('token');
    localStorage.removeItem('token');
    localStorage.removeItem('user');
    return axios
      .post(`${API_URL}/logout`, {
        token: token
      });
  }
}

export default new AuthService();
