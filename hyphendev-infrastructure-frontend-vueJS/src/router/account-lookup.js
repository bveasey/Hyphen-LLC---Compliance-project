import { authenticated } from './middleware'

export default {
    path: '/account-lookup',
    redirect: '/account-lookup',
    component: () => import('@/layouts/Default.vue'),
    children: [
        {
            path: '/account-lookup',
            component: () => import('@/pages/AccountLookup.vue'),
            name: 'account lookup',
            meta: {
                title: 'Account Lookup',
                middleware: authenticated,
            }
        }
    ]
}