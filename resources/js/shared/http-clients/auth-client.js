const BASE_URL = `auth`;
export default {
    login(formValue) {
        return axios.post(`${BASE_URL}/login`, formValue);
    },
    verifyToken() {
        return axios.get(`${BASE_URL}/verify-token`);
    },
    logout() {
        return axios.get(`${BASE_URL}/logout`);
    },
    getCurrentUser() {
        return axios.get(`${BASE_URL}/current-user`);
    }
}