import { authenticated } from './middleware'

export default {
    path: '/',
    redirect: '/dashboard',
    component: () => import('@/layouts/Default.vue'),
    children: [
        {
            path: '/dashboard',
            component: () => import('@/pages/Dashboard.vue'),
            name: 'dashboard',
            meta: {
                title: 'Dashboard',
                middleware: authenticated,
            }
        }
    ]
}