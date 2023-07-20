const BASE_URL = `sales-returns-invoices`;
export default {
    getSaleInvoices(page, pageSize, text) {
        return axios.get(`${BASE_URL}?page=${page}&page_size=${pageSize}&text=${text}`);
    },
    getCustomers() {
        return axios.get(`${BASE_URL}/customers`);
    },
    getDelegates() {
        return axios.get(`${BASE_URL}/delegates`);
    },
    getItems() {
        return axios.get(`${BASE_URL}/items`);
    },
    getStores() {
        return axios.get(`${BASE_URL}/stores`);
    },
    createSaleInvoice(formValue){
        return axios.post(`${BASE_URL}`,formValue);
    },
    update(formValue){
        return axios.put(`${BASE_URL}`,formValue);
    },
    getInvoiceCategories(){
        return axios.get(`${BASE_URL}/invoice-categories`);
    },
    updateTax(formValue){
        return axios.put(`${BASE_URL}/tax`,formValue);
    },
    updateDiscount(formValue){
        return axios.put(`${BASE_URL}/discount`,formValue);
    },
    delete(id){
        return axios.delete(`${BASE_URL}/${id}`);
    }
}