import { createContent, getContent, updateContent , deleteContent} from '../api/content';

export default {
  namespaced: true,

  state:{

    content:[]

  },
  actions:{

    async createContent({commit}, data)
    {
      const response = await createContent(
                              data.html_content,
                              data.json_content,
                              data.has_comment,
                              data.is_comment,
                              data.parrent_id,
                              data.anonymous,
                              data.visibility);

      commit('likes/updateLikes',  {content_id:response.data.content.id, likes:[]}, { root: true });
      response.data.content.show_comment=false;
      commit('prependContent', response.data.content);


    },


    setContent ({commit}, content) {
      commit('setContent', content);
    },

    async getContent({commit}, payload)
    {

      const response = await getContent(payload);
      commit('setContent', response.data.content.data);

    },

    clearContent({commit},  content){
      commit('clearContent', content);
    },
    async updateContent ({commit}, data) {
      const response = await updateContent(
                              data.id,
                              data.html_content,
                              data.json_content,
                              data.has_comment,
                              data.is_comment,
                              data.parrent_id,
                              data.anonymous,
                              data.visibility);
      commit('updateContent', response.data);

    },
    deleteContent({commit},  content_id){
      const response = deleteContent(content_id);
      commit('deleteContent', content_id);
    },
    appendContent({commit},  content){
      commit('appendContent', content);
    },
    prependContent({commit},  content){
      commit('prependContent', content);
    },


  },

  getters:{
    getContent: state => state.content,
    getContentById: (state) => (id) => {
        return state.content.find(content => content.id === id)
    },
    getLikesById: (state) => (id) => {

         return state.likes.find(like => like.content_id == id);
    }
  },
  mutations:{
    setContent(state, content){
      //we have to make sure the inital state of show_comment is false

      for(var i=0, length= content.length; i < length; i++)
      {
        content[i].show_comment=false;
      }
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
    }

  }

};
