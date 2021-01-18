/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

/* Import all the components from ./components and subfolders */
// import upperFirst from 'lodash/upperFirst';
// import camelCase from 'lodash/camelCase';
// let requireComponent = require.context(
//   './components',
//   true,
//   /[A-Z]\w+\.(vue|js)$/
// )
// requireComponent.keys().forEach(fileName => {
//     const componentConfig = requireComponent(fileName);
//     /* Manage subfolder fileName */
//     fileName = fileName.slice(fileName.lastIndexOf('/'), fileName.length);
//     const componentName = upperFirst(
//         camelCase(
//             fileName.replace(/^\.?\/?(.*)\.\w+$/, '$1')
//         )
//     );
//     Vue.component(
//         componentName,
//         componentConfig.default || componentConfig
//     );
// });

window.Vue = require('vue').default

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

const files = require.context('./', true, /\.vue$/i)
files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

// Vue.component('example-component', require('./components/ExampleComponent.vue').default);

import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'

// Import Bootstrap an BootstrapVue CSS files (order is important)
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'

// Make BootstrapVue available throughout your project
Vue.use(BootstrapVue)
// Optionally install the BootstrapVue icon components plugin
Vue.use(IconsPlugin)

import App from './App.vue'

/* Load router */
import router from './router'

/* Load vuex */
import store from './store/store';


const app = new Vue({
    el: '#app',
    components: {
        App
    },
    router,
    store,
    created () {
        const loginData = localStorage.getItem('user');
        if (loginData) {
            const userData = JSON.parse(loginData);
            this.$store.commit('setUserData', userData);
        }
        axios.interceptors.response.use(
            response => response,
            error => {
              if (error.response.status === 401) {
                this.$store.dispatch('auth/logout')
              }
              return Promise.reject(error)
            }
          )
    },
    render: h => h(App)
});
