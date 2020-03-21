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
        <h3>Groups</h3>
    </div>
    <div class="row" v-for="item in group">

      <div class="col-lg-6">{{item.name}}</div>
      <div class="col-lg-6">{{item.pivot.status }}</div>

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
    import {upload} from '../../helper/upload'
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
        let vm=this

        upload(function(res){


          this.user.avatar=res;

          let data = {
              avatar: res

          };
          axios.put('/api/user/'+this.user.id, data)
              .then(({data}) => {

                vm.$store.commit('user/setUser', data);
              })
              .catch(({response}) => {
                vm.show=true;
                vm.error=response.data.errors;
              });

        }.bind(this));


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

                this.$store.commit('user/setUser', data);
              })
              .catch(({response}) => {
                this.show=true;
                this.error=response.data.errors;
              });

      }
    },
    computed:{
            user:{
              get: function(){
                return this.$store.getters["user/getUser"];
              }

            },
            group:{
              get: function(){
                return this.$store.getters["user/getGroup"];
              },
            },
            isAuth(){
              return this.$store.getters["user/isAuth"];
            }
      }
}
</script>
