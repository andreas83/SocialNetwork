import { getUser } from '../api/user';

export default {
  namespaced: true,
  state:{
    isAuthenticated:false,
    user:{},
    groups:[]

  },
  actions:{


      setUser ({commit}, user) {

        commit('setUser', user);

      },
      async getUser({commit})
      {
        try {

            const response = await getUser();
          
            commit('setUser', response.data);
            return response;

        } catch (error) {
            // handle the error here
        }
      },
      setAuth ({commit}, status) {
        commit('setAuth', status);
      }
    },

    getters:{

      getGroup: state => state.groups,
      getUser: state => state.user,
      isAuth: state =>  state.isAuthenticated
    },
    mutations:{
      setUser(state, data){
        state.user=data.user;
        state.groups=data.groups;
      },
      setAuth(state, isAuth){
        state.isAuthenticated=isAuth;
      }
    }

};
