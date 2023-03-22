import BaseService from '@/services/baseService';

class Service extends BaseService {
    get() {
        return this.api.get('services');
    }

    search(id, query) {
        return this.api.post('services/' + id + '/lookup-by-email', {'email': query})
    }
}

export default new Service();
