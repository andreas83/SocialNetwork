<template>

    <div id="groupBox">

      <div class="avatar" v-if="group.avatar" v-bind:style="{ 'background-image': 'url(' + getThumbnail(group.avatar, 150, 150) + ')' }" />


      <p>{{group.description}}</p>
      <p>Members: {{group.members}}</p>
      <p>Posts: {{group.posts}}</p>


      <button v-if="isModerator">{{$t("form.group.changePicture")}}</button>
      <button v-if="isModerator">{{$t("form.group.changeDescription")}}</button>
      <button v-if="isModerator">{{$t("form.group.deleteGroup")}}</button>

      <button v-if="!isMember" @click="joinGroup(group.id)" class="btn default">Join</button>
      <button v-if="isMember" @click="leaveGroup(group.id)" class="btn default">Leave</button>



      <div v-if="membership" class="membership">
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
          <p v-if="isModerator"  @click="declineMembership(user.id)">remove</p>

        </div>
        <b  v-if="membership.pending.length > 0" >Pending Users</b>

        <div class="user"  v-for="user in membership.pending" >
          <router-link :to="{ name: 'user', params: {name:user.name} }">
            <div class="avatar" v-if="user.avatar" v-bind:style="{ 'background-image': 'url(' + getThumbnail(user.avatar, 150, 150) + ')' }" />
            <p>{{user.name}}</p>

          </router-link>
          <p v-if="isModerator" ><b @click="approveMembership(user.id)">+ approve</b> / <b @click="declineMembership(user.id)">- decline</b></p>
        </div>
      </div>


    </div>


</template>
<script>
    import {mapGetters, mapActions} from 'vuex';
    import {getThumbnail} from '../../helper/resize'
    import {getGroupMembers} from '../../store/api/group'
    import {leave,join, approveMember, declineMember} from '../../store/api/group'
    export default {
    name:"GroupBox",
    props:{

      group_id:{
        default:false
      },
    },
    data() {

        return {

          group_id:0,
          membership: {
            moderators:[],
            members:[],
            pending:[]
          }

        };
    },
    created(){

    },
    mounted(){
        this.group_id=this.$route.params.id;
        let vm=this;
        getGroupMembers(this.$route.params.id).then(function(res){
          vm.membership=res.data;

        });
    },
    methods: {

      approveMembership(user_id)
      {
          approveMember(this.group_id, user_id);
      },
      declineMembership(user_id)
      {
          declineMember(this.group_id, user_id);
      },


      joinGroup(id){

        join(id).then(vm.$forceUpdate());

      },
      leaveGroup(id){

        leave(id).then(vm.$forceUpdate());

      },

      getThumbnail,
        ...mapActions('groups', ['getGroup']),
        ...mapActions('user', ['getUser']),
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
      isMember(){

        for (var i = 0; i < this.myGroups.length; i++) {

          if(this.myGroups[i].id==this.$route.params.id)
          {
              return true;
          }

        }
        return false;
      },
      group(){
        return this.$store.getters["groups/getGroupById"](this.$route.params.id);
      },
      myGroups(){
        return this.$store.getters["user/getGroup"];
      },
      isAuth(){
        return this.$store.getters["user/isAuth"];
      }
    },
    watch:{
      // isMember(){
      //   let vm=this;
      //   vm.membership=false;
      //   getGroupMembers(this.$route.params.id).then(function(res){
      //     vm.membership=res.data;
      //
      //   });
      // }
    },
  }
</script>
