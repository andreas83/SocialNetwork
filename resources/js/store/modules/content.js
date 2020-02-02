export default {
  namespaced: true,
  state:{

    content:[]
  },
  action:{
    setContent ({commit}, content) {
      commit('setContent', content);
    },
    clearContent({commit},  content){
      commit('clearContent', content);

    },
    appendContent({commit},  content){
      commit('appendContent', content);

    }

  },

  getters:{
    getContent: state => state.content,
  },
  mutations:{
    setContent(state, content){
      state.content=content;
    },
    appendContent(state, content){

      state.content.push(content);
    },
    clearContent(state, content){

      state.content=[];
    }

  }

};
