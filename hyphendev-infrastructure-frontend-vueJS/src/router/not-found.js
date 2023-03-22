export default {
    path: '/:pathMatch(.*)*',
    component: () => import('@/pages/NotFound.vue'),
    name: '404',
    meta: {
        title: '404 Error',
    }
}