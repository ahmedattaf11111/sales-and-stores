const BASE_URL = `accounts`;
export default {
    getAccounts(page, pageSize, text, account_type_id, is_master) {
        return axios.get(`${BASE_URL}?page=${page}&page_size=${pageSize}
        &text=${text}
        &account_type_id=${account_type_id}
        &is_master=${is_master}`);
    },
    getAllAccounts() {
        return axios.get(`${BASE_URL}/all`);
    },
    getTypes() {
        return axios.get(`${BASE_URL}/types`);
    },
    create(formValue) {
        return axios.post(`${BASE_URL}`, formValue);
    },
    update(formValue) {
        return axios.put(`${BASE_URL}`, formValue);
    }
}