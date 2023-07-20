const BASE_URL = `supplier_account_statement`;
export default {
    getSuppliers() {
        return axios.get(`${BASE_URL}/suppliers`);
    },
    getTotalAccountStatementReport(supplier_id, from_date, to_date) {
        return axios.get(`${BASE_URL}/total-report?supplier_id=${supplier_id}&from_date=${from_date}&to_date=${to_date}`);
    },
    getPurchaseInvoiceReport(page, pageSize, supplier_id, from_date, to_date) {
        return axios.get(`${BASE_URL}/purchase-invoice-report?page=${page}&page_size=${pageSize}
        &supplier_id=${supplier_id}&from_date=${from_date}&to_date=${to_date}`);
    },
    getPurchaseReturnInvoiceReport(page, pageSize, supplier_id, from_date, to_date) {
        return axios.get(`${BASE_URL}/purchase-return-invoice-report?page=${page}&page_size=${pageSize}
        &supplier_id=${supplier_id}&from_date=${from_date}&to_date=${to_date}`);
    },
    getCollectExchangeReport(page, pageSize, supplier_id, from_date, to_date) {
        return axios.get(`${BASE_URL}/collect-exchange-report?page=${page}&page_size=${pageSize}
        &supplier_id=${supplier_id}&from_date=${from_date}&to_date=${to_date}`);
    }
       
}