import { createRouter, createWebHistory } from 'vue-router'
import { global } from './middleware'
import { useAuthenticationStore } from "@/stores";
import { AuthenticationService } from "@/services";

const routes = Object.values(
    import.meta.glob([
        './*.js',
        '!./index.js',
        '!./middleware.js',
    ], {
        import: 'default',
        eager: true
    })
)

const router = createRouter({
    history: createWebHistory(),
    routes,
    scrollBehavior() { //to, from, savedPosition
        return { top: 0 }
    },
})

router.beforeEach(async (to, from) => {
    const authStore = useAuthenticationStore();
    if (!authStore.storeIsReady) {
        await AuthenticationService.initilize();
    }

    let routeMiddleware = to.meta?.middleware ?? []

    if (typeof routeMiddleware === 'function') routeMiddleware = [routeMiddleware]

    routeMiddleware = [global, ...routeMiddleware]

    for (let i = 0; i < routeMiddleware.length; i++) {
        const routeName = routeMiddleware[i]({
            from,
            router,
            to,
            authStore,
        })

        if (typeof routeName === 'string') {
            console.log({ next: routeName })
            return { name: routeName }
        }
    }
})

export default router