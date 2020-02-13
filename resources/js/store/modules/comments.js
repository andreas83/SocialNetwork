import { getComment } from '../api/comment';


export default {
  namespaced: true,

  state:{


    comments:[]
  },
  actions:{
    setContent ({commit}, content) {
      commit('setContent', content);
    },

    getContent ({commit}, content) {
      commit('getContent', content);
    },
    clearContent({commit},  content){
      commit('clearContent', content);
    },
    updateContent ({commit}, content) {
      commit('updateContent', content);
    },
    delteContent({commit},  content_id){
      commit('delteContent', content_id);
    },
    appendContent({commit},  content){
      commit('appendContent', content);
    },
    prependContent({commit},  content){
      commit('prependContent', content);
    },


    updateLikes ({commit}, likes) {
      commit('updateLikes', likes);
    },
    setLikes ({commit}, likes) {
      commit('setLikes', likes);
    },

    async getComment({ state, commit }, content_id){

      try {
          const response = await getComment();
          commit('appendComment', response.data);
      } catch (error) {
          // handle the error here
      }



    },
    setComment ({commit}, comment) {
      commit('setComment', comment);
    },
    appendComment({commit},  comment){
      commit('appendComent', comment);
    },

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
    },
    getComment: (state) =>  {

         return state.comments;
    },
  },
  mutations:{
    setContent(state, content){
      state.content=content;
    },
    prependContent(state, content){
      state.content.splice(0, 0, content);

    },
    appendContent(state, content){

      state.content.push(content);
    },
    updateContent (state, data) {
      const index = state.content.findIndex(item => item.id == data.content.id);
      if (index !== -1)
      {
        state.content.splice(index, 1, data.content);
      }
      else{
        state.content.push(data);
      }
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
    },

    setComment (state, comment) {
      state.comments=comment;
    },
    appendComment(state, comment){

      state.comments.push(comment);
    },

  }

};
