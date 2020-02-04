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
    deelteContent({commit},  content_id){
      commit('deelteContent', content_id);

    },
    appendContent({commit},  content){
      commit('appendContent', content);

    },
    updateLikes ({commit}, likes) {
      commit('updateLikes', likes);
    },
    setLikes ({commit}, likes) {
      commit('setLikes', likes);
    }

  },

  getters:{
    getContent: state => state.content,
    getContentById: (state) => (id) => {
        return state.content.find(content => content.id === id)
    },
    getLikesById: (state) => (id) => {

         return state.likes.find(like => like.content_id == id);
    },
    getLikes: (state) =>  {

         return state.likes;
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
    deleteContent(state, content_id){
      const index = state.content.findIndex(item => item.id == content_id);
      state.content.splice(index, 1);
    },
    updateLikes (state, data) {
      const index = state.likes.findIndex(item => item.content_id == data.content_id);
      if (index !== -1)
      {
        state.likes.splice(index, 1, data);
      }
      else{
        state.likes.push({"content_id": data.content_id, "likes": data.likes});
      }

    },
    setLikes (state, likes) {
      state.likes=likes;
    }

  }

};
