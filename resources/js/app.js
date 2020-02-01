require('./bootstrap');


window.Vue = require('vue');
window.Pusher = require('pusher-js');

import Vue from 'vue'
import Vuex from 'vuex'
import VueRouter from 'vue-router'
import VueI18n from 'vue-i18n'
import i18n from './i18n'
import {router} from './routes.js';
import {store} from './store/store.js';

import Layout from './components/Layout'

Vue.use(VueI18n);
Vue.use(Vuex);
Vue.use(VueRouter);

Vue.config.ignoredElements = ['comment', 'date', 'author', 'comments', 'actions',
'description', 'authorBox']


Vue.component('Register', require('./components/user/register').default);
Vue.component('Login', require('./components/user/login').default);
Vue.component('Strean', require('./components/stream/stream').default);
Vue.component('Actions', require('./components/stream/actions').default);
Vue.component('Editor', require('./components/stream/editor').default);
Vue.component('Comments', require('./components/stream/comments').default);

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
      axios.interceptors.request.use(
        (config) => {
          config.headers['Authorization'] = "Bearer "+localStorage.getItem('token');
          return config;
        },

        (error) => {
          return Promise.reject(error);
        }
      );
      axios.get('/api/user', )
          .then(({data}) => {
            this.$store.commit('user/setUser', data);
            this.$store.commit('user/setAuth', true);
          })
          .catch(({response}) => {
            this.$store.commit('user/setAuth', false);
            this.error=response;
          });

    },
    methods:{

    },
    computed:{
      isAuth(){
        return this.$store.getters["user/isAuth"];
      }
    }
});
