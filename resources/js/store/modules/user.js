export default {
  namespaced: true,
  state:{
    isAuthenticated:false,
    user:{}
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
      getUser: state => state.user,
      isAuth: state =>  state.isAuthenticated
    },
    mutations:{
      setUser(state, user){
        state.user=user;
      },
      setAuth(state, isAuth){
        state.isAuthenticated=isAuth;
      }
    }

};
