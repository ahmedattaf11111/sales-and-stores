const BASE_URL = `suppliers`;
export default {
    getSuppliers(page, pageSize, text) {
        return axios.get(`${BASE_URL}?page=${page}&page_size=${pageSize}&text=${text}`);
    },
    getCategories(){
        return axios.get(`${BASE_URL}/categories`);
    },
    create(formValue) {
        return axios.post(`${BASE_URL}`, formValue);
    },
    update(formValue) {
        return axios.put(`${BASE_URL}`, formValue);
    }
}