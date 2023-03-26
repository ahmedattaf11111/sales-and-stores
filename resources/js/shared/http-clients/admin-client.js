const BASE_URL = `admins`;
export default {
    getAdmins(page, pageSize, text) {
        return axios.get(`${BASE_URL}?page=${page}&page_size=${pageSize}&text=${text}`);
    },
    getAllTreasuries() {
        return axios.get(`${BASE_URL}/treasuries`);
    },
    create(formValue) {
        return axios.post(`${BASE_URL}`, formValue);
    },
    update(formValue) {
        return axios.put(`${BASE_URL}`, formValue);
    }
}