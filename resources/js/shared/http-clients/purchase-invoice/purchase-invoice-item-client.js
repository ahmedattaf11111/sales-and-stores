const BASE_URL = `purchase-invoice-items`;
export default {
    getPurchaseInvoiceItems(page, pageSize, purchaseInvoiceId, text) {
        return axios.get(`${BASE_URL}/${purchaseInvoiceId}?page=${page}&page_size=${pageSize}&text=${text}`);
    },
    getItems() {
        return axios.get(`${BASE_URL}/items`);
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
    isPurchaseInvoiceApproved(id) {
        return axios.get(`${BASE_URL}/is-purchase-invoice-approved/${id}`,);
    }
}