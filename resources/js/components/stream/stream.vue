<template>

  <div class="row">
    <div class="col-lg-12">
      <share-dialog :edit="isEdit" :content_id="content_id"></share-dialog>
    </div>
    <div class="col-lg-12">
      <div class="row-0 streamitem " v-for="data in content" v-if="user_id==false || user_id==data.user_id">
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

            <content v-html="data.html_content">

            </content>

            <actions :content=data  v-on:toggleComment="toggleComment">
            </actions>

            <comments :parrent_content=data v-if="data.show_comment">
            </comments>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>

export default {
  name: "Stream",
    props:{
      user_id:{
        default:false
      }
    },
    data() {
        return{
          isEdit:false,
          content_id:0,
          error:""
        }
      },
      mounted(){

        this.getContent();

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
        getContent(){
            let data={};

            if(this.user_id>0)
            {
                data.user_id=this.user_id;
            }
            axios.get('/api/content', {"params":data})
                .then(({data}) => {

                  for(var i=0, length= data.content.data.length; i < length; i++)
                  {

                    data.content.data[i].show_comment=false;
                  }

                  this.$store.commit('content/setContent', data.content.data);

                })
                .catch(({response}) => {

                });
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
