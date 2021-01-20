<template>
  <div class="group overview">
    <div class="row">

      <div class="col-lg-12  col-md-12">
        <div class="form-field">

          <input id="search" type="text" name="search" placeholder="search " v-model="search" />

        </div>
      </div>
    </div>
    <div class="row">
      <div v-for="item in group" class="col-lg-3">

          <div @click="showGroup(item.id, item.name)" class="group preview" v-if="item.avatar" v-bind:style="{ 'background-image': 'url(' + getThumbnail(item.avatar, 150, 150) + ')' }" />

          <h4>{{item.name}}</h4>

          <p>{{item.description}}</p>

          <button @click="showGroup(item.id, item.name)">Show</button>

          <button v-if="!isMember(item.id)" @click="joinGroup(item.id)" class="btn default">Join</button>
          <button v-if="isMember(item.id)" @click="leaveGroup(item.id)" class="btn default">Leave</button>
      </div>

    </div>
  </div>
</template>
<script>
import {mapGetters, mapActions} from 'vuex';
import {getThumbnail} from '../../helper/resize'
import {leave,join} from '../../store/api/group'
import { debounce } from 'lodash'
export default {
    name: "GroupOverview",

    data() {
        return{
          search:"",
          error:""
        }
      },
      async created(){

        await this.getGroup({});

      },
      mounted(){

      },
      methods:{
        ...mapActions('user', ['getUser']),

        isMember(id)
        {


          for (var i = 0; i < this.member.length; i++) {

            if(id==this.member[i].id)
            {
              return true;
            }
          }
          return false;

        },
        async joinGroup(id){
          join(id).then(this.getUser());

        },
        async leaveGroup(id){
          leave(id).then(this.getUser());

        },
        showGroup(id, name){
          this.$router.push({ name: 'Group', params: { name: name, id: id } })

        },
        getThumbnail,
        ...mapActions('groups', ['getGroup', 'setGroup']),

      },
      computed:{

        ...mapGetters({
          group: 'groups/getGroup',

          member: 'user/getGroup'
        }),
        isAuth(){
          return this.$store.getters["user/isAuth"];
        }
      },
      watch: {
        search: debounce(function () {
          this.setGroup([]);
          if(this.search.length>1)
          {
             this.getGroup({search:this.search});
          }
        }, 300)
      }
    }
</script>
<style>

</style>
