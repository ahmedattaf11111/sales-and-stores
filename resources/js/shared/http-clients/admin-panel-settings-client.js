const BASE_URL = `admin-panel-settings`;
export default {
    getAdminPanelSettings() {
        return axios.get(`${BASE_URL}`);
    },
    update(formValue) {
        return axios.post(`${BASE_URL}`,formValue);
    },
    getGeneralAccounts(){
        return axios.get(`${BASE_URL}/general-accounts`);
    }
}