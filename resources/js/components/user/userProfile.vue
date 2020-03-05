<template>
  <div class="row card profile settings">
    <div class="col-lg-6  col-md-12 ">
      <div id="avatar" v-if="user.avatar" v-bind:style="{ 'background-image': 'url(' + getThumbnail(user.avatar, 100,100) + ')' }" />
      <br/>
      <button class="btn default" v-on:click="openFileDialog" value="default">{{$t('form.avatar.upload')}}</button>

    </div>
    <div class="col-lg-6  col-md-12 ">
      <h2>{{user.name}}</h2>
    </div>

    <div class="col-lg-6  col-md-12 ">
      <div class="form-field">
        <label for="password">New {{$t('form.password')}}</label>
        <input id="password" type="password" autocomplete="new-password"  v-model="password" />
      </div>
      <div class="form-field">
        <label for="mail">{{$t('form.email')}}</label>
        <input id="mail" type="email"  placeholder="email " v-model="user.email"/>
      </div>
    </div>
    <div class="col-lg-6  col-md-12 ">
      <div class="form-field">
        <label for="bio">{{$t('form.bio')}}</label>
        <textarea id="bio" v-model="user.bio"></textarea>
      </div>
    </div>
    <div class="col-lg-6  col-md-12 ">
      <div class="form-field right">
          <button class="btn default" v-on:click="save" value="default">{{$t('form.save')}}</button>
      </div>
    </div>

  </div>
</div>

</template>
<script>

    import {getThumbnail} from '../../helper/resize'
    export default {
    name:"UserProfile",
    data() {

        return {
          avatar:"",


          password:"",

        };
    },
    mounted(){

    },

    methods: {
      getThumbnail,
      openFileDialog(){

        var element = document.createElement('div');
        element.innerHTML = '<input  type="file">';
        let fileInput = element.firstChild;
        let vm=this;
        let token=this.user.api_token;
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
                vm.user.avatar=result.path[0];
                vm.$store.commit('user/setUser', vm.user);
                let data = {

                    avatar: vm.user.avatar,



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
      save(){

          let data = {
              email: this.user.email,
              avatar: this.user.avatar,
              password: this.password,
              bio:this.user.bio,

          };
          axios.put('/api/user/'+this.user.id, data)
              .then(({data}) => {

                this.$store.commit('user/setUser', data.user);
              })
              .catch(({response}) => {
                this.show=true;
                this.error=response.data.errors;
              });

      }
    },
    computed:{
            user(){
              return this.$store.getters["user/getUser"];
            },
            isAuth(){
              return this.$store.getters["user/isAuth"];
            }
      }
}
</script>
