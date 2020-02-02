export default {
  namespaced: true,
  state:{

    content:[],
    likes:[]
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

    },
    setLikes ({commit}, likes) {
      commit('setLikes', likes);
    }

  },

  getters:{
    getContent: state => state.content,
    getContentById: (state) => (id) => {
        return state.content.find(content => content.id === id)
    }
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
    },

    setLikes (state, likes) {
      state.likes=likes;
    }

  }

};
