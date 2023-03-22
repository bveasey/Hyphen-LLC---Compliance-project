import BaseService from '@/services/baseService';
import { useAuthenticationStore } from "@/stores";

class Service extends BaseService {
    initilize() {
        const lsToken = localStorage.getItem('token') ?? false;

        const authStore = useAuthenticationStore();
        if (lsToken) {
            return this.getUser().then(() => {
                authStore.setStoreIsReady();
            });
        }
        authStore.setStoreIsReady();
    }

    getUser() {
        return this.api.get('user')
            .then(({data}) => {
                useAuthenticationStore().setUser(data);
            })
            .catch((error) => {
                useAuthenticationStore().clearTokenAndRedirect();
            });
    }

    login(email, password) {
        return this.api.post('login', {email, password})
            .then(({data}) => {
                localStorage.setItem('token', data.token);
                useAuthenticationStore().setUser(data.user);
            });
    }

    logout() {
        return this.api.post('logout')
            .then(() => {
                useAuthenticationStore().clearTokenAndRedirect();
            });
    }
}

export default new Service();
