<template>

  <div class="row">

    <div class="col-lg-12  col-md-12">
      <div class="form-field">

        <input id="search" type="text" name="search" placeholder="search " v-model="search" />
        <button  @click="showCreate=true" class="btn default">+</button>
        <GroupCreate v-if="showCreate"></GroupCreate>

      </div>
    </div>
    <div v-if="showCreate!=true" v-for="item in group" class="col-lg-3">
        <div class="group preview" v-if="item.avatar" v-bind:style="{ 'background-image': 'url(' + getThumbnail(item.avatar, 150, 150) + ')' }" />

        <h2>{{item.name}}</h2>
        <p>{{item.description}}</p>

        <button @click="showGroup(item.id, item.name)">Show</button>
        <button @click="joinGroup" class="btn default">Join</button>
    </div>

  </div>
</template>
<script>
import {mapGetters, mapActions} from 'vuex';
import {getThumbnail} from '../../helper/resize'
import { debounce } from 'lodash'
export default {
    name: "GroupOverview",

    data() {
        return{
          search:"",
          error:"",
          showCreate:false
        }
      },
      async created(){

        await this.getGroup({});

      },
      mounted(){

      },
      methods:{
        joinGroup(){

        },

        showGroup(id, name){
          this.$router.push({ name: 'Group', params: { name: name, id: id } })

        },
        getThumbnail,
        ...mapActions('groups', ['getGroup', 'setGroup']),

      },
      computed:{

        ...mapGetters({
          group: 'groups/getGroup'
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
