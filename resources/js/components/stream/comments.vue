<template>

  <div class="row-0 comment-container">
    <share-dialog :edit="isEdit" :content_id="content_id" :parrent_id=parrent_content.id :is-comment="isComment"></share-dialog>
    <div class="comment-list row-0">
      <div class="comment col-lg-12"  v-for="data in content">

        <picture>
          <img :src="data.avatar" />
        </picture>
        <author>
          {{data.name}}
        </author>
        <date>{{data.created_at}}</date>
        <button class="btn default small" @click="deleteContent(data.id)">{{$t("form.delete")}}</button>
        <button class="btn default small" @click="editContent(data.id)">{{$t("form.edit")}}</button>
        <content v-html="data.html_content"></content>
        <actions :content=data  v-on:toggleComment="toggleComment"></actions>
      </div>
    </div>

  </div>
</template>
<script>

export default {
    name: "Comments",
    props:{
      parrent_content:
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
      mounted(){
        this.getComments();
      },
      methods:{
        deleteContent(id){
          axios.delete('/api/content/'+id).then(({data}) => {
            this.$store.commit('content/deleteContent', id);
          })
        },

        editContent(id){
          this.isEdit=true;
          this.content_id=id;
        },

        getComments(){

          axios.get('/api/content/comments/'+this.parrent_content.id)
              .then(({data}) => {
                this.content=data.content.data;

              })
              .catch(({response}) => {

              });
        },
        toggleComment(id){
          console.log(this.$refs.id);
        }
      },
      computed:{
        isAuth(){
          return this.$store.getters["user/isAuth"];
        }
      }
    }
</script>
<style>

</style>
