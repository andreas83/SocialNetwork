<template>

  <div class="row-0">
    <div class="col-lg-12" v-if="isAuth">
      <share-dialog :edit="isEdit" :reshare="isReshare" @updated="onUpdated" @saved="onSaved" :content_id="id"></share-dialog>

    </div>
    <div v-bind:class=css_stream_size  >
      <div class="row-0 streamitem " v-for="data in content" v-if="((user_id==false || user_id==data.user_id)  && (content_id==false || data.id==content_id)) && data.is_comment=='false'">

        <div class="row card" >

          <div class="col-lg-12  col-md-12">
            <router-link :to="{ name: 'user', params: {name:data.name, user_id:data.user_id} }">
              <picture>
                <img v-if="data.avatar" :src="getThumbnail(data.avatar, 40, 40)" />
              </picture>
              <author >
                {{data.name}}

              </author>
            </router-link>


            <date>{{  data.created_at |  moment("from", "now", true) }}</date>


            <button class="btn default small"  @click="permalink(data.id)">#{{data.id}}</button>
            <button class="btn default small" v-if="data.user_id==user.id" @click="deleteContent(data.id)">{{$t("form.delete")}}</button>
            <button class="btn default small" v-if="data.user_id==user.id" @click="editContent(data.id)">{{$t("form.edit")}}</button>



            <content v-html="data.html_content">

            </content>

            <likes :content=data v-on:reshareContent="reshareContent" v-on:toggleComment="toggleComment">
            </likes>

            <comments :parent_content=data v-if="data.show_comment">
            </comments>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-md-12 ">
      <UserBox :user_id="user_id"></UserBox>
    </div>
  </div>
</template>
<script>


import javascript from 'highlight.js/lib/languages/javascript';
import {mapGetters, mapActions} from 'vuex';
import {getThumbnail} from '../../helper/resize'

export default {
  name: "Stream",
    props:{
      css_stream_size:{
        default:"col-lg-12"
      },
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
          isReshare: false,
          id:0,
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
        getThumbnail,

        swipeRightHandler(direction, event, el){
            event.target.classList.toggle('slide-out-right');
              console.log("swipeRightHandler");
        },
        swipeLeftHandler(direction){
            event.target.classList.toggle('slide-out-left');
        },
        scroll () {
          window.onscroll = () => {

            if (window.innerHeight + window.pageYOffset+200 >= document.body.offsetHeight) {

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
        onSaved(value)
        {
          this.isReshare=false;
        },

        onUpdated (value) {
          this.isEdit=false;
        },
        editContent(id){
          this.isEdit=true;
          this.id=id;

        },
        reshareContent(id)
        {

          this.isReshare=true;
          this.id=id;
          document.body.scrollTop = 0; // For Safari
          document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
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
           this.css_stream_size="col-lg-8 col-md-12";
           this.getContent();
        }
      },
    }
</script>
<style>

</style>
