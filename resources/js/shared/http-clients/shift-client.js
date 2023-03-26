const BASE_URL = `shifts`;
export default {
    getShifts(page, pageSize) {
        return axios.get(`${BASE_URL}?page=${page}&page_size=${pageSize}`);
    },
    getCurrentShift() {
        return axios.get(`${BASE_URL}/current-shift`);
    },
    getAdminTreasuries() {
        return axios.get(`${BASE_URL}/admin-treasuries`);
    },
    create(formValue) {
        return axios.post(`${BASE_URL}`, formValue);
    },
}