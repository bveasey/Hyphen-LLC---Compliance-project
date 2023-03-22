import CrudService from '@/services/crudService';

class Service extends CrudService {
    constructor() {
        super({ url: 'roles', snackbarLabel: 'Role' });
    }
}

export default new Service();
