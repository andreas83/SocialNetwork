import { getLikesById, createLike } from '../api/likes';


export default {
  namespaced: true,

  state:{

    likes:[]

  },
  actions:{
    async createLike({commit}, data)
    {
      const response = await createLike(data.key, data.content_id);
      commit('updateLikes', response.data.likes);
    },
    async getLikes({commit}, payload)
    {
      try {

          const response = await getLikesById(payload);
          commit('updateLikes', response.data.likes);
      } catch (error) {
          // handle the error here
      }
    },

    updateLikes ({commit}, likes) {
      commit('updateLikes', likes);
    },
    setLikes ({commit}, likes) {
      commit('setLikes', likes);
    },

  },

  getters:{

    getLikes: (state) =>  {

         return state.likes;
    },

  },
  mutations:{


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

  }

};
