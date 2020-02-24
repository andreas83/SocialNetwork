<template>

  <div class="row">
    <div class="col-lg-12" v-if="isAuth">
      <share-dialog :edit="isEdit" @updated="onUpdated" :content_id="content_id"></share-dialog>

    </div>
    <div class="col-lg-12">
      <div class="row-0 streamitem " v-for="data in content" v-if="((user_id==false || user_id==data.user_id)  && (content_id==false || data.id==content_id)) && data.is_comment=='false'">

        <div class="row card" >

          <div class="col-lg-12  col-md-12">
            <router-link :to="{ name: 'user', params: {name:data.name, user_id:data.user_id} }">
              <picture>
                <img :src="data.avatar" />
              </picture>
              <author >
                {{data.name}}

              </author>
            </router-link>
            <date>{{data.created_at}}</date>

            <button class="btn default small" v-if="data.user_id==user.id" @click="deleteContent(data.id)">{{$t("form.delete")}}</button>
            <button class="btn default small" v-if="data.user_id==user.id" @click="editContent(data.id)">{{$t("form.edit")}}</button>
            <button class="btn default small"  @click="permalink(data.id)" >{{$t("permalink")}}</button>

            <content v-html="data.html_content">

            </content>

            <likes :content=data  v-on:toggleComment="toggleComment">
            </likes>

            <comments :parent_content=data v-if="data.show_comment">
            </comments>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>


import javascript from 'highlight.js/lib/languages/javascript'
import {mapGetters, mapActions} from 'vuex';
export default {
  name: "Stream",
    props:{
      user_id:{
        default:false
      },
      content_id:{
        default:false
      }
    },
    data() {
        return{
          isEdit:false,

          error:""
        }
      },
      async created (){

        //await this.getContent();
        await this.getMoreContent({next_id:false, user_id: this.user_id, content_id: this.content_id});
      },
      mounted(){

        this.scroll();
      },
      methods:{

        scroll () {
          window.onscroll = () => {
            let bottomOfWindow = document.documentElement.scrollTop + window.innerHeight === document.documentElement.offsetHeight;

            if (bottomOfWindow) {

              var next_id = Math.min.apply(Math, this.content.map(function(o){ return o.id }));

              this.getMoreContent({next_id:next_id, user_id: this.user_id, content_id: this.content_id});
            }
          };
        },

        ...mapActions('content', ['getContent', 'getMoreContent', 'deleteContent']),
        deleteContent(id){
          axios.delete('/api/content/'+id).then(({data}) => {
            this.$store.commit('content/deleteContent', id);
          })

        },
        permalink(id){
          this.$router.push({ name: 'permalink', params: { id: id } })

        },
        onUpdated (value) {
          this.isEdit=false;
        },
        editContent(id){
          this.isEdit=true;
          this.content_id=id;

        },

        toggleComment(id){

          for(var i=0, length= this.content.length; i < length; i++)
          {
            if(id==this.content[i].id)
            {
              if(this.content[i].show_comment)
              {
                this.content[i].show_comment=false;
              }else{
                this.content[i].show_comment=true;
              }
            }
          }



        }
      },
      computed:{
        content(){
          return this.$store.getters["content/getContent"];
        },
        user(){
          return this.$store.getters["user/getUser"];
        },
        isAuth(){
          return this.$store.getters["user/isAuth"];
        }
      },
      watch:{

        user_id(){

           this.getContent();
        }
      },
    }
</script>
<style>

</style>
