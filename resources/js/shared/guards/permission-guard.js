import authClient from '../http-clients/auth-client';
import global from "../global";
export default (to, from, next) => {
    return authClient.hasPermission(to.name.replace("x_",""))
        .then((res) => { res.data.has_permission === true ? next() : next({ path: global.AUTH_REDIRECT }) });
}
