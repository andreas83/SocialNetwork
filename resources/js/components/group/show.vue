<template>

  <div class="row">

    <div class="row card profile" v-bind:style="{ 'background-image': 'url(' + getThumbnail(group.background, 1400, 500) + ')' }">

            <div class="col-lg-12  col-md-12 center">
              <div id="avatar" v-if="group.avatar" v-bind:style="{ 'background-image': 'url(' + getThumbnail(group.avatar, 100, 100) + ')' }" />
              <h2>{{group.name}}</h2>

            </div>
            <button class="btn defualt" v-if="isModerator" v-on:click="changeBackground" >{{$t('form.background.upload')}}</button>
    </div>

    <stream  :group_id="group.id"></stream>
  </div>
</template>
<script>
import {mapGetters, mapActions} from 'vuex';
import {getThumbnail} from '../../helper/resize'
import {upload} from '../../helper/upload'
export default {
    name: "Group",

    data() {
        return{
          // group:{
          //   id:"",
          //   name:"",
          //   description:"",
          //   avatar:"",
          //   background:"",
          // },
          error:""
        }
      },
       mounted(){

         this.getGroup({id : this.$route.params.id});


      },

      methods:{

        getThumbnail,
        ...mapActions('groups', ['getGroup']),

        changeBackground(){
          let vm=this;
          upload(function(res){
            vm.group.background=res;
            vm.$store.commit('group/setGroup', vm.group);
            let data = {
                background: res,
            };
            axios.put('/api/group/'+vm.group.id, data)
                .then(({data}) => {
                  vm.$store.commit('group/setGroup', data.group);
                })
                .catch(({response}) => {
                  vm.show=true;
                  vm.error=response.data.errors;
                });
          });
        },

      },
      computed:{
        isModerator(){

          for (var i = 0; i < this.myGroups.length; i++) {
            if(this.myGroups[i].id==this.$route.params.id && this.myGroups[i].pivot.is_moderator==1)
            {
                return true;
            }

          }
          return false;
        },
        myGroups(){
          return this.$store.getters["user/getGroup"];
        },
        group(){
          return this.$store.getters["groups/getGroupById"](this.$route.params.id);
        },
        isAuth(){
          return this.$store.getters["user/isAuth"];
        }
      }
    }
</script>
<style>

</style>
