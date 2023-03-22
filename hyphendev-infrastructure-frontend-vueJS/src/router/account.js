import { authenticated } from './middleware';

export default {
    path: '/account',
    component: () => import('@/layouts/Account.vue'),
    redirect: '/account/profile',
    children: [
        {
            path: 'profile',
            name: 'profile',
            component: () => import('@/pages/account/Profile.vue'),
            meta: {
                title: 'Profile',
                middleware: authenticated,
            }
        },
        {
            path: 'password',
            name: 'password',
            component: () => import('@/pages/account/Password.vue'),
            meta: {
                title: 'Password',
                middleware: authenticated,
            }
        },
    ]
}