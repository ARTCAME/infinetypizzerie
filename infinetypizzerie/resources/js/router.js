import Dashboard from './views/Dashboard.vue'
import Login from './views/Login.vue'
import Router from 'vue-router';
import Vue from 'vue'

Vue.use(Router)

const router = new Router ({
    base: process.env.BASE_URL,
    mode: 'history',
    routes: [
        {
            path: '/',
            name: 'home',
            component: Dashboard,
            meta: {
                auth: true,
            }
        },
        {
            path: '/login',
            name: 'login',
            component: Login,
        },
    ]
});
router.beforeEach((to, from, next) => {
    const loggedIn = localStorage.getItem('username');
    if (to.matched.some(record => record.meta.auth) && !loggedIn) {
        next('/login');
        return;
    }
    next();
});
export default router;
