import Vue from 'vue'
import Vuex from 'vuex'
import user from './modules/user'
import content from './modules/content'
import likes from './modules/likes'
import groups from './modules/groups'
Vue.use(Vuex)


export const store = new Vuex.Store({

  modules:{
      user,
      content,
      likes,
      groups
  },

});
