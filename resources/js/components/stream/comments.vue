<template>

  <div class="row-0 comment-container">
    <share-dialog  v-if="isAuth" :edit="isEdit"  @updated="onUpdated"  :content_id="content_id" :parent_id=parent_content.id :is-comment="isComment"></share-dialog>

    <button class="btn" v-if="!isAuth" @click="this.$router.push('/')"> Login</button>

    <div class="comment-list row-0">

      <div class="comment col-lg-12"  v-for="data in comments" v-if="parent_content.id==data.parent_id ">
        <header class="row-0">
          <div class="col-lg-10">
          <router-link :to="{ name: 'user', params: {name:data.name, user_id:data.user_id} }">

            <picture>
              <div class="avatar"  v-if="data.avatar" v-bind:style="{ 'background-image': 'url(' + getThumbnail(data.avatar, 100, 100) + ')' }" />
            </picture>
            <author >
              {{data.name}}

            </author>
          </router-link>
          <span @click="permalink(data.id)">#{{data.id}}</span>

          <date>{{  data.created_at |  moment("from", "now", true) }}</date>
          </div>
          <div class="actions col-lg-2">
            <button class="btn default small" v-if="data.user_id==user.id" @click="deleteContent(data.id)">{{$t("form.delete")}}</button>
            <button class="btn default small" v-if="data.user_id==user.id" @click="editContent(data.id)">{{$t("form.edit")}}</button>
          </div>
        </header>
        <content v-html="data.html_content"></content>

        <likes :content=data></likes>

      </div>
    </div>

  </div>
</template>
<script>
import {mapGetters , mapActions} from 'vuex';
import {getThumbnail} from '../../helper/resize'
export default {
    name: "Comments",
    props:{
      parent_content:
      {
        default:false
      }
    },
    data() {
        return{

          isComment:true,
          isEdit:false,

          content_id:0,
          content:[],
          error:""
        }
      },
      async created(){

        this.getComment( this.parent_content.id );

      },
      methods:{
        getThumbnail,
        ...mapActions('content', ['getComment']),

        deleteContent(id){
          axios.delete('/api/content/'+id).then(({data}) => {
            this.$store.commit('content/deleteContent', id);
          })
        },
        onUpdated (value) {
          this.isEdit=false;
        },
        editContent(id){
          this.isEdit=true;
          this.content_id=id;
        },

      },
      computed:{
        user(){
          return this.$store.getters["user/getUser"];
        },
        comments(){
          return  this.$store.getters["content/getCommentById"](this.parent_content.id);
        },
        isAuth(){
          return this.$store.getters["user/isAuth"];
        }
      }
    }
</script>
<style>

</style>
