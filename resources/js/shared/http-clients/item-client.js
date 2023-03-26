const BASE_URL = `items`;
export default {
    getItems(page, pageSize, text, item_category_id, type) {
        return axios.get(`${BASE_URL}?page=${page}&page_size=${pageSize}
        &text=${text}
        &item_category_id=${item_category_id}
        &type=${type}`);
    },
    getItemCategories() {
        return axios.get(`${BASE_URL}/categories`);
    },
    getUnitOfMeasures() {
        return axios.get(`${BASE_URL}/unit-of-measures`);
    },
    create(formValue) {
        return axios.post(`${BASE_URL}`, formValue);
    },
    update(formValue) {
        return axios.post(`${BASE_URL}/update`, formValue);
    },
    delete(id) {
        return axios.delete(`${BASE_URL}/${id}`);
    }
}