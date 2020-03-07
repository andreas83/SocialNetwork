require('./bootstrap');


window.Vue = require('vue');
window.Pusher = require('pusher-js');

import Vue from 'vue'

import Vuex from 'vuex'
import VueRouter from 'vue-router'
import VueI18n from 'vue-i18n'
import VueSocialauth from 'vue-social-auth'
import VueMoment from 'vue-moment'
import i18n from './i18n'
import {router} from './routes.js';
import {store} from './store/store.js';

import Layout from './components/Layout'




Vue.use(VueSocialauth, {
  providers: {
    github: {
      clientId: process.env.MIX_GITHUB_KEY,
      redirectUri: process.env.MIX_GITHUB_REDIRECT_URI
    },
    twitter: {
      clientId: process.env.MIX_TWITTER_KEY,
      redirectUri: process.env.MIX_TWITTER_REDIRECT_URI
    },
    facebook: {
      clientId: process.env.MIX_FACEBOOK_KEY,
      redirectUri: process.env.MIX_FACEBOOK_REDIRECT_URI
    },
  }
});

Vue.use(VueI18n);
Vue.use(Vuex);
Vue.use(VueRouter);
Vue.use(VueMoment);

Vue.config.ignoredElements = ['comment', 'date', 'author', 'comments', 'actions',
'description', 'authorBox']


Vue.component('Register', require('./components/user/register').default);
Vue.component('Login', require('./components/user/login').default);
Vue.component('Stream', require('./components/stream/stream').default);
Vue.component('Likes', require('./components/stream/likes').default);
Vue.component('ShareDialog', require('./components/stream/shareDialog').default);
Vue.component('Comments', require('./components/stream/comments').default);
Vue.component('UserBox', require('./components/user/userBox').default);
Vue.component('GroupBox', require('./components/group/groupBox').default);
Vue.component('GroupCreate', require('./components/group/new').default);

Vue.prototype.$http = axios;


const app = new Vue({
    el: '#app',
    components: { Layout },
    store: store,

    router,
    i18n,
    data() {
      return {}
    },
    beforeCreate()
    {
      axios.interceptors.request.use(
        (config) => {
          config.headers['Authorization'] = "Bearer "+localStorage.getItem('token');
          return config;
        },

        (error) => {
          return Promise.reject(error);
        }
      );
    },
    mounted(){

      axios.get('/api/user' )
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
