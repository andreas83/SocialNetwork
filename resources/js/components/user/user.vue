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

      this.getUser();

    },
    methods: {
      getThumbnail,
      changeBackground(){

        var element = document.createElement('div');
        element.innerHTML = '<input  type="file">';
        let fileInput = element.firstChild;
        let vm=this;
        let token= localStorage.getItem('token');

        fileInput.addEventListener('change', function() {
          let formData = new FormData();

          for (let i = 0; i < fileInput.files.length; i++) {
              let file = fileInput.files[i];
              formData.append('upload[]', file, file.name);
          }
          var xhr = new XMLHttpRequest();



          xhr.open('POST', '/api/content/upload', true);
          xhr.setRequestHeader('Authorization', 'Bearer ' + token);
          xhr.onload = function () {
              if (xhr.status === 200) {

                const result = JSON.parse(xhr.responseText);
                //vm.avatar=result.path[0];
                vm.user.background=result.path[0];
                vm.$store.commit('user/setUser', vm.user);
                let data = {

                    background: vm.user.background,



                };
                axios.put('/api/user/'+vm.user.id, data)
                    .then(({data}) => {

                      vm.$store.commit('user/setUser', data.user);
                    })
                    .catch(({response}) => {
                      vm.show=true;
                      vm.error=response.data.errors;
                    });

              } else {
                  vm.error = xhr.responseText;
              }
          };
          xhr.send(formData);


        });

        fileInput.click();
      },
      getUser(){
        //  this.$route.params.username

          axios.get('/api/user/public/?name='+this.$route.params.name)
              .then(({data}) => {
                this.user=data;
                console.log(data);
                // for(var i=0, length= data.content.data.length; i < length; i++)
                // {
                //
                //   data.content.data[i].show_comment=false;
                // }
                //
                // this.$store.commit('content/setContent', data.content.data);

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
