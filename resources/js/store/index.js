import Vue from "vue";
import Vuex from "vuex";

import {auth} from "./modules/auth";
import {operations} from "./modules/operations";
import {categories} from "./modules/categories";

Vue.use(Vuex);

const store = new Vuex.Store({
    modules: {
        auth,
        operations,
        categories
    }
})

export default store
