const BASE_URL = `purchase-invoices`;
export default {
    getPurchaseInvoices(page, pageSize, text, storeId, supplierId) {
        return axios.get(`${BASE_URL}?page=${page}&page_size=${pageSize}
        &text=${text}&store_id=${storeId}&supplier_id=${supplierId}`);
    },
    getStores() {
        return axios.get(`${BASE_URL}/stores`);
    },
    getSuppliers() {
        return axios.get(`${BASE_URL}/suppliers`);
    },
    create(formValue) {
        return axios.post(`${BASE_URL}`, formValue);
    },
    update(formValue) {
        return axios.put(`${BASE_URL}`, formValue);
    },
    delete(id) {
        return axios.delete(`${BASE_URL}/${id}`,);
    },
    approve(formValue) {
        return axios.put(`${BASE_URL}/approve`, formValue);
    },
    getCurrentShift() {
        return axios.get(`${BASE_URL}/current-shift`);
    }
}