import { defineStore } from 'pinia';
import { computed, ref } from "vue";

export default defineStore('authentication', () => {
    const user = ref(null);
    const storeIsReady = ref(false);

    function setUser(userValue) {
        user.value = userValue;
    }

    function setStoreIsReady() {
        storeIsReady.value = true;
    }

     const clearTokenAndRedirect = (to = '/') => {
        setUser(null);
        localStorage.removeItem('token');
        window.location = to;
    }

    const authenticated = computed(() => user.value !== null);

    return { authenticated, setUser, setStoreIsReady, user, storeIsReady, clearTokenAndRedirect};
})