require('./bootstrap');


window.Vue = require('vue');
window.Pusher = require('pusher-js');

import Vue from 'vue'
import Vuex from 'vuex'
import VueI18n from 'vue-i18n'
import i18n from './i18n'
import {router} from './routes.js';
import {store} from './store/store.js';

import Layout from './components/Layout'

Vue.use(VueI18n);
Vue.use(Vuex);

const app = new Vue({
    el: '#app',
    components: { Layout },
    store: store,
    router,
    i18n,
    data() {
      return {}
    },
    mounted(){
      console.log("iam loaded");
    }
});
