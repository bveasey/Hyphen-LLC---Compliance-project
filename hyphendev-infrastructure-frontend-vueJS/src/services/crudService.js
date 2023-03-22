/**
 * Use this service if you want to show success and error messages by default
 * Set the snackbarLabel to the singular version of whatever resource you are performing the crud ops on
 * The default message will be: `${snackbarLabel} has been ${method} successfully!`
 *
 * Pass snackbarError (boolean) in your components or sub services to override the default bahavior
 * Pass successMessage (string) in your components or sub services to override the default bahavior
 *
 */
import { parseUrl } from '@/lib/utils';
import BaseService from './baseService';

export default class extends BaseService {
    #url;
    #snackbarLabel;
    constructor({ url, snackbarLabel = 'Resource' }) {
        super();
        this.#snackbarLabel = snackbarLabel;
        this.#url = url;
    }

    index({ urlParams = null, config = null } = {}) {
        return this.api.get(parseUrl(this.#url, urlParams), config);
    }

    show(id, { urlParams = null, config = null } = {}) {
        return this.api.get(`${parseUrl(this.#url, urlParams)}/${id}`, config);
    }

    store(data = {}, { urlParams = null, config = null } = {}) {
        return this.api.post(
            parseUrl(this.#url, urlParams),
            this.convertToFormData(data),
            this.formatConfig(config, 'created')
        );
    }

    update(id, data = {}, { urlParams = null, config = null } = {}) {
        return this.api.put(
            `${parseUrl(this.#url, urlParams)}/${id}`,
            this.convertToFormData(data),
            this.formatConfig(config, 'updated')
        );
    }

    destroy(id, { urlParams = null, config = null } = {}) {
        return this.api.delete(
            `${parseUrl(this.#url, urlParams)}/${id}`,
            this.formatConfig(config, 'deleted')
        );
    }

    formatConfig(config, method) {
        return {
            snackbarError: true,
            successMessage: `${
                this.#snackbarLabel
            } has been ${method} successfully!`,
            ...config,
        };
    }
}