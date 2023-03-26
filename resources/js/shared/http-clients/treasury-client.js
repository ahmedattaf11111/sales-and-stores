const BASE_URL = `treasuries`;
export default {
    getTreasuries(page, pageSize, text) {
        return axios.get(`${BASE_URL}?page=${page}&page_size=${pageSize}&text=${text}`);
    },
    getAllTreasuries() {
        return axios.get(`${BASE_URL}/all`);
    },
    create(formValue) {
        return axios.post(`${BASE_URL}`, formValue);
    },
    update(formValue) {
        return axios.put(`${BASE_URL}`, formValue);
    }
}