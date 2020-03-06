<template>

  <div class="row">
    <div class="col-lg-6  col-md-12 ">
      <div id="avatar" v-if="group.avatar" v-bind:style="{ 'background-image': 'url(' + getThumbnail(group.avatar, 100,100) + ')' }" />
      <br/>
      <button class="btn default" v-on:click="openFileDialog" value="default">{{$t('form.avatar.upload')}}</button>

    </div>

    <div class="col-lg-12  col-md-12">
      <div class="form-field">
        <input  type="text" placeholder="name " v-model="autocomplete" />
      </div>
    </div>
    <div class="col-lg-12  col-md-12">
      <div class="form-field">
        <textarea v-model="group.description"></textarea>
      </div>
    </div>
    <div class="col-lg-12  col-md-12">
      <div class="form-field">
        <select v-model="group.status">
          <option>Public</option>
          <option>private</option>
        </select>
      </div>
    </div>
    <button class="btn default">+</button>
    <button class="btn default">+</button>
    </div>
  </div>

  </div>
</template>
<script>
import {mapGetters, mapActions} from 'vuex';
import {getThumbnail} from '../../helper/resize'
export default {
    name: "GroupCreate",

    data() {
        return{
          autocomplete:"",
          group:{
            description:"",
            avatar: "",

          },
          error:""
        }
      },
      async created(){

        await this.getGroup({});

      },
      mounted(){

      },
      methods:{

        getThumbnail,
        ...mapActions('groups', ['getGroup']),

      },
      computed:{

        ...mapGetters({
          autocompleteResult: 'groups/getGroup'
        }),
        isAuth(){
          return this.$store.getters["user/isAuth"];
        }
      }
    }
</script>
<style>

</style>
