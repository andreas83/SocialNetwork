<template>
  <div>
    <div class="row card profile"  v-bind:style="{ 'background-image': 'url(' + getThumbnail(user.background, 1400, 500) + ')' }">

            <div class="col-lg-12  col-md-12 center">
              <div id="avatar" v-if="user.avatar" v-bind:style="{ 'background-image': 'url(' + getThumbnail(user.avatar, 100, 100) + ')' }" />
              <h2>  {{this.$route.params.name}}</h2>

            </div>
            <button class="btn defualt" v-if="isAuth && loggedInUser.id==user.id" v-on:click="changeBackground" >{{$t('form.background.upload')}}</button>
    </div>

    <stream css_size="col-lg-12 " :user_id="user.id"></stream>


  </div>
</template>
<script>
    import {upload} from '../../helper/upload'
    import {getThumbnail} from '../../helper/resize'
    export default {
    name:"User",
    data() {

        return {
          user:{
            id:0,
            name:"",
            bio:"",
            avatar:"",
            background:"none"
          }

        };
    },
    mounted(){
      window.scroll(0,0);
      this.getUser();

    },
    methods: {
      getThumbnail,

      changeBackground(){
        let vm=this;
        upload(function(res){
          vm.user.background=res;

          let data = {
              background: vm.user.background,
          };
          axios.put('/api/user/'+vm.user.id, data)
              .then(({data}) => {
                vm.$store.commit('user/setUser', data);
              })
              .catch(({response}) => {
                vm.show=true;
                vm.error=response.data.errors;
              });
        });
      },
      getUser(){


          axios.get('/api/user/public/?name='+this.$route.params.name)
              .then(({data}) => {
                this.user=data;

              })
              .catch(({response}) => {

              });
      },

    },
    computed:{
      loggedInUser(){
        return this.$store.getters["user/getUser"];
      },
      isAuth(){
        return this.$store.getters["user/isAuth"];
      }
    },
    watch: {
        $route(to, from) {
          this.getUser();
        }
  }
}
</script>
