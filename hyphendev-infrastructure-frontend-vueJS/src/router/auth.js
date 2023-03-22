import { guest } from './middleware';

export default {
    path: '/auth',
    component: () => import('@/layouts/Auth.vue'),
    redirect: '/auth/login',
    children: [
        {
            path: 'login',
            name: 'login',
            component: () => import('@/pages/auth/Login.vue'),
            meta: {
                title: 'Login',
                middleware: guest,
            }
        },
        {
            path: 'signup',
            name: 'signupn',
            component: () => import('@/pages/auth/Signup.vue'),
            meta: {
                title: 'Sign Up',
                middleware: guest,
            }
        },
        {
            path: 'forgot-password',
            name: 'forgot-password',
            component: () => import('@/pages/auth/ForgotPassword.vue'),
            meta: {
                title: 'Forgot Password',
                middleware: guest,
            }
        },
        {
            path: 'reset-password',
            name: 'reset-password',
            component: () => import('@/pages/auth/ResetPassword.vue'),
            meta: {
                title: 'Reset Password',
                middleware: guest,
            }
        }
    ]
}