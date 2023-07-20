const BASE_URL = `shifts`;
export default {
    getShifts(page, pageSize,text) {
        return axios.get(`${BASE_URL}?page=${page}&page_size=${pageSize}&text=${text}`);
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
    closeShift() {
        return axios.get(`${BASE_URL}/close`);
    },
}