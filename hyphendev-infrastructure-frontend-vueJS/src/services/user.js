import CrudService from '@/services/crudService';

class Service extends CrudService {
    constructor() {
        super({ url: 'users', snackbarLabel: 'User' });
    }
}

export default new Service();
