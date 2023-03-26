const BASE_URL = `treasury-transactions`;
export default {
    getTreasuryTransactions(page, pageSize, type) {
        return axios.get(`${BASE_URL}?page=${page}&page_size=${pageSize}&type=${type}`);
    },
    getAccounts() {
        return axios.get(`${BASE_URL}/accounts`);
    },
    create(formValue) {
        return axios.post(`${BASE_URL}`, formValue);
    },
    getGeneralInfo(){
        return axios.get(`${BASE_URL}/general-info`);
    }
}