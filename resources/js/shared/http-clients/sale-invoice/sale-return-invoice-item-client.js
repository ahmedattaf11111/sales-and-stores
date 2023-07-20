const BASE_URL = `sales-returns-invoice-items`;
export default {
    addItemToInvoice(formValue) {
        return axios.post(`${BASE_URL}`, formValue);
    },
    deleteItem(formValue) {
        return axios.post(`${BASE_URL}/delete`, formValue);
    },
    getInvoiceItems(saleInvoiceId) {
        return axios.get(`${BASE_URL}/${saleInvoiceId}`);
    },
    getBatches(storeId,itemId) {
        return axios.get(`${BASE_URL}/batches/${storeId}/${itemId}`);
    },
}