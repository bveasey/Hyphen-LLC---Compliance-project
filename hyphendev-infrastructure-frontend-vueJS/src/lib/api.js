import axios from 'axios';
import Qs from 'qs';
import { useSnackbarStore, useAuthenticationStore } from '@/stores';

const apiServiceTypes = Object.freeze({
    BASE: 'base_api',
    APP: 'app_api',
});

function createApi(config) {
    const defaultConfig = {
        baseURL: '/',
        withCredentials: true,
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
        },
        paramsSerializer: function (params) {
            return Qs.stringify(params, { arrayFormat: 'brackets' });
        },
    };
    return axios.create({
        ...defaultConfig,
        ...config,
    });
}

function requestConfig(config) {
    const token = localStorage.getItem('token') ?? null;
    if (token) config.headers.Authorization = 'Bearer ' + token;
    return config;
}

async function responseError(error) {
    const status = error?.response?.status ?? false;

    if (!status) {
        useSnackbarStore().error('📡 API | Network/Server error');
        return Promise.reject({ error, status });
    }

    const authenticationStore = useAuthenticationStore();

    // reset currentUser store if 401 or 419 is returned
    if ([401, 419].includes(status)) {
        return authenticationStore.clearTokenAndRedirect();
    }

    // logout user and redirect to maintenance page
    if (status === 503) {
        return authenticationStore.clearTokenAndRedirect('/maintenance');
    }

    if (status >= 500) {
        useSnackbarStore().error(
            error?.response?.data?.message ??
            'Internal server error. Please try again later.'
        );
        return Promise.reject({ error, status });
    }

    return Promise.reject({ error, status });
}

export { apiServiceTypes, createApi, requestConfig, responseError };