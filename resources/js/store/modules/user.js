export default {
  namespaced: true,
  state:{
    isAuthenticated:false,
    user:{},
    groups:[]

  },
  action:{
      setUser ({commit}, user) {

        commit('setUser', user);

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
