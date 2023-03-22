import { authenticated } from './middleware'

export default {
    path: '/user-management',
    redirect: '/user-management',
    component: () => import('@/layouts/Default.vue'),
    children: [
        {
            path: '/user-management',
            component: () => import('@/pages/user-management/UserManagement.vue'),
            name: 'user management',
            meta: {
                title: 'User Management',
                middleware: authenticated,
            }
        }
    ]
}