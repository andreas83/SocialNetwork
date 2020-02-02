export default {
  namespaced: true,
  state:{

    content:{}
  },
  action:{
      setContent ({commit}, content) {
        commit('setContent', content);
      }
    },

    getters:{
      getContent: state => state.content,
    },
    mutations:{
      setContent(state, content){
        state.content=content;
      },

    }

};
