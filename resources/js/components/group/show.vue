<template>

  <div class="row">

    <div class="col-lg-12">

        <div class="group preview" v-if="group.avatar" v-bind:style="{ 'background-image': 'url(' + getThumbnail(group.avatar, 150, 150) + ')' }" />

        <h2>{{group.name}}</h2>
        
        <button class="btn defualt" v-if="group.is_moderator" v-on:click="changeBackground" >{{$t('form.background.upload')}}</button>

    </div>
    <stream  :group_id="group.id"></stream>
  </div>
</template>
<script>
import {mapGetters, mapActions} from 'vuex';
import {getThumbnail} from '../../helper/resize'
export default {
    name: "Group",

    data() {
        return{

          error:""
        }
      },
      async created(){

        await this.getGroup({id : this.$route.params.id});


      },
      mounted(){

      },
      methods:{

        getThumbnail,
        ...mapActions('groups', ['getGroup']),

      },
      computed:{


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
