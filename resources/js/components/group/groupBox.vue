<template>

    <div id="groupBox">

      <div class="avatar" v-if="group.avatar" v-bind:style="{ 'background-image': 'url(' + getThumbnail(group.avatar, 150, 150) + ')' }" />


      <p>{{group.description}}</p>



      <button>{{$t("form.group.changePicture")}}</button>
      <button>{{$t("form.group.changeDescription")}}</button>

      <button v-if="!isMember(group.id)" @click="joinGroup(group.id)" class="btn default">Join</button>
      <button v-if="isMember(group.id)" @click="leaveGroup(group.id)" class="btn default">Leave</button>

      <div class="membership">
        <b v-if="membership.moderators.length > 0" >Moderators</b>
        <div class="user" v-for="user in membership.moderators" >
          <router-link :to="{ name: 'user', params: {name:user.name} }">
            <div class="avatar" v-if="user.avatar" v-bind:style="{ 'background-image': 'url(' + getThumbnail(user.avatar, 150, 150) + ')' }" />
            <p>{{user.name}}</p>
          </router-link>
        </div>

        <b v-if="membership.members.length > 0" >Members</b>
        <div  class="user"  v-for="user in membership.members" >
          <router-link :to="{ name: 'user', params: {name:user.name} }">
            <div class="avatar" v-if="user.avatar" v-bind:style="{ 'background-image': 'url(' + getThumbnail(user.avatar, 150, 150) + ')' }" />
            <p>{{user.name}}</p>
          </router-link>
        </div>
        <b  v-if="membership.pending.length > 0" >Pending Users</b>

        <div class="user"  v-for="user in membership.pending" >
          <router-link :to="{ name: 'user', params: {name:user.name} }">
            <div class="avatar" v-if="user.avatar" v-bind:style="{ 'background-image': 'url(' + getThumbnail(user.avatar, 150, 150) + ')' }" />
            <p>{{user.name}}</p>
          </router-link>
        </div>
      </div>


    </div>


</template>
<script>
    import {mapGetters, mapActions} from 'vuex';
    import {getThumbnail} from '../../helper/resize'
    import {getGroupMembers} from '../../store/api/group'
    import {leave,join} from '../../store/api/group'
    export default {
    name:"GroupBox",
    props:{

      group_id:{
        default:false
      },
    },
    data() {

        return {

          membership: {
            moderators:[],
            members:[],
            pending:[]
          }

        };
    },
    mounted(){

        this.getGroup({id : this.$route.params.id});
        let vm=this;
        getGroupMembers(this.$route.params.id).then(function(res){
          vm.membership=res.data;

        });
    },
    methods: {
      isMember(id){

        return this.getMembership.find(group=>group.id==id);
      },
      refresh(){
        this.getUser();
        let vm=this;
        getGroupMembers(this.$route.params.id).then(function(res){
          vm.membership=res.data;

        });
      },
      async joinGroup(id){
        join(id).then(this.refresh());

      },
      async leaveGroup(id){
        leave(id).then(this.refresh());

      },

      getThumbnail,
        ...mapActions('groups', ['getGroup']),
        ...mapActions('user', [ 'getUser']),
    },
    computed:{
      group(){
        return this.$store.getters["groups/getGroupById"](this.$route.params.id);
      },
      getMembership(){
        return this.$store.getters["user/getGroup"];
      },
      isAuth(){
        return this.$store.getters["user/isAuth"];
      }
    },
    watch:{

    },
  }
</script>
