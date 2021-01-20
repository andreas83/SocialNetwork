<template>

  <div class="row">
    <div class="col-lg-6  col-md-12 ">
      <div class="group preview large" v-if="group.avatar" v-bind:style="{ 'background-image': 'url(' + getThumbnail(group.avatar, 250,250) + ')' }" />
      <br/>



    </div>

    <div class="col-lg-12  col-md-12">
      <div class="form-field">
        <input  type="text" placeholder="name " v-model="autocomplete" /><button class="btn default" v-on:click="openFileDialog" value="default">{{$t('form.avatar.upload')}}</button>

        <div class="row-0 autoCompleteGroup" @click="selectGroup(item)" v-for="item in autocompleteResult">
            <div class="col-lg-8">
              {{item.name}}
            </div>
            <div class="col-lg-4">
              <div class="small avatar" v-bind:style="{ 'background-image': 'url(' + getThumbnail(item.avatar, 50, 50) + ')' }"></div>
            </div>

        </div>

      </div>
    </div>
    <div class="col-lg-12  col-md-12">
      <div class="form-field">
        <textarea v-model="group.description"></textarea>
      </div>
    </div>
    <div class="col-lg-12  col-md-12">
      <div class="form-field">
        <select v-model="group.visibility">
          <option value="public">Public</option>
          <option value="private">Private</option>
        </select>
      </div>

    </div>
    <div class="col-lg-12  col-md-12">
      <button @click="save" class="btn default">Save</button>
      <button @click="cancle" class="btn default">Cancle</button>
    </div>
    </div>

</template>
<script>
import {mapGetters, mapActions} from 'vuex';
import {getThumbnail} from '../../helper/resize'
import {upload} from '../../helper/upload'

import { debounce } from 'lodash'

export default {
    name: "GroupCreate",
    props:{
      suggestion:{
        default:false
      }
    },
    data() {
        return{
          autocomplete:"",
          group:{
            description:"",
            avatar: "",
            visibility:[
              { text: 'Public', value: 'public' },
              { text: 'Private', value: 'private' },

            ],
          },
          error:""
        }
      },
      async created(){
          this.setGroup([]);
      },
      mounted(){

      },
      methods:{

        getThumbnail,
        openFileDialog(){
          let vm=this;
          upload(function(res){
            vm.group.avatar=res;

          });
        },
        ...mapActions('groups', ['setGroup', 'getGroup', 'createGroup']),
        cancle(){
            this.$emit('cancled');
        },
        save(){
          let data = {
              name: this.autocomplete,
              description: this.group.description,
              visibility: this.group.visibility,
              avatar:this.group.avatar,
              background: "",

          };
          let vm=this;
          this.createGroup(data).then(function(res)
          {

            vm.$emit('saved', res.data.groups);
          });

        }
      },
      computed:{

        ...mapGetters({
          autocompleteResult: 'groups/getGroup'
        }),
        isAuth(){
          return this.$store.getters["user/isAuth"];
        }
      },
      watch: {
        suggestion:function(val){
          this.autocomplete=val;
        },

        autocomplete: debounce(function () {
          this.setGroup([]);
          if(this.autocomplete.length>1)
          {
             this.getGroup({search:this.autocomplete});
          }
        }, 300)
      }

    }
</script>
<style>

</style>
