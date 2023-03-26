const BASE_URL = `stores`;
export default {
    getStores(page, pageSize, text) {
        return axios.get(`${BASE_URL}?page=${page}&page_size=${pageSize}&text=${text}`);
    },
    create(formValue) {
        return axios.post(`${BASE_URL}`, formValue);
    },
    update(formValue) {
        return axios.put(`${BASE_URL}`, formValue);
    },
    delete(id) {
        return axios.delete(`${BASE_URL}/${id}`);
    }
}