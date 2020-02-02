<template>

  <div class="row">
    <div class="col-lg-12">
      <share-dialog ></share-dialog>
    </div>
    <div class="col-lg-12">

      <div class="row card" v-for="data in content">
        <div class="col-lg-1  col-md-1">
        <picture>
          <img src="https://via.placeholder.com/50" />
        </picture>
        </div>
        <div class="col-lg-10  col-md-10">
          <author>
            {{data.name}}
          </author>
          <date>{{data.created_at}}</date>
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
</template>
<script>

export default {
    name: "Stream",

    data() {
        return{
          content:[],
          showComment:false,
          error:""
        }
      },
      mounted(){

        this.getContent();
      },
      methods:{

        getContent(){

            axios.get('/api/content')
                .then(({data}) => {
                  console.log(data.content.data.length);
                  for(var i=0, length= data.content.data.length; i < length; i++)
                  {

                    data.content.data[i].show_comment=false;
                  }
                  this.content=data.content.data;
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
        isAuth(){
          return this.$store.getters["user/isAuth"];
        }
      }
    }
</script>
<style>

</style>
