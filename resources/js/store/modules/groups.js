import { getGroup, createGroup } from '../api/group';


export default {
  namespaced: true,

  state:{

    group:[]

  },
  actions:{
    async createGroup({commit}, data)
    {
      const response = await createGroup(data.name, data.description, data.avatar, data.visibility);
      commit('updateGroup', response.data.groups);
      return response;
    },
    async getGroup({commit}, payload)
    {
      try {

          const response = await getGroup(payload.id, payload.name, payload.search, payload.limit, payload.random);

          for(let i=0; i < response.data.groups.length; i++)
          {

              commit('updateGroup', response.data.groups[i]);
          }


      } catch (error) {
          // handle the error here
      }
    },

    updateGroup ({commit}, group) {
      commit('updateGroup', group);
    },
    setGroup ({commit}, group) {
      commit('setGroup', group);
    },

  },
  getters:{

    getGroup: (state) =>  {

         return state.group;
    },
    getGroupById: (state) => (id) => {

        return state.group.find(data => data.id == id)
    }
  },
  mutations:{

    updateGroup (state, data) {
      const index = state.group.findIndex(item => item.id == data.id);
      if (index !== -1)
      {
        state.group.splice(index, 1, data);
      }
      else{

        state.group.push(data);
      }

    },
    setGroup (state, group) {
      state.group=group;
    },

  }

};
