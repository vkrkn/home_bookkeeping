require('./bootstrap');

import Vue from "vue";
import VueRouter from "vue-router";
import VueCookies from "vue-cookies";
import store from "./store";
import router from "./router";

import App from "./App.vue";

import { library } from '@fortawesome/fontawesome-svg-core'
import { faTimes, faPen } from '@fortawesome/free-solid-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

library.add([faTimes, faPen])

Vue.component('font-awesome-icon', FontAwesomeIcon)

Vue.use(VueRouter)
Vue.use(VueCookies)

Vue.$cookies.config('2d');
router.beforeEach((to, from, next) => {
    document.title = to.meta.title
    if (to.name !== 'login' && to.name !== 'registration' && !store.state.auth.login.api_token)
        next({ name: 'login' })
    else
        next()
})

const app = new Vue({
    el: '#app',
    router,
    store,
    components: { App }
})
