<template>
  <div class="action">
    <button v-if="content.has_comment=='true' && content.is_comment=='false'" class="icon-comment" v-on:click="toggleComment"></button>
    <button v-if="content.has_comment=='false' && content.is_comment=='false'" class="icon-comment-empty" v-on:click="toggleComment"/>

    <button @click="saveLike('heart')" class="icon-heart">{{getLikesByKey("heart")}}</button>
    <button @click="saveLike('happy')" class="icon-happy">{{getLikesByKey("happy")}}</button>
    <button @click="saveLike('wink')" class="icon-wink">{{getLikesByKey("wink")}}</button>

    <button @click="saveLike('like')" class="icon-like">{{getLikesByKey("like")}}</button>
    <button @click="saveLike('devil')"  class="icon-devil">{{getLikesByKey("devil")}}</button>
    <button @click="saveLike('coffee')"  class="icon-coffee">{{getLikesByKey("coffee")}}</button>
    <button @click="saveLike('sunglasses')"  class="icon-sunglasses">{{getLikesByKey("sunglasses")}}</button>
    <button @click="saveLike('displeased')"  class="icon-displeased">{{getLikesByKey("displeased")}}</button>
    <button @click="saveLike('beer')"  class="icon-beer">{{getLikesByKey("beer")}}</button>

  </div>
</template>
<script>

export default {
    name: "Actions",
    props:{
      content:false
    },
    data() {
        return{

          error:""
        }
      },
      mounted(){
        this.getLikes();
        console.log(this.getLikesByKey("beer"));
      },
      methods:{
        toggleComment(){

            this.$emit('toggleComment', this.content.id);

        },
        saveLike(key){
          let data={
            key:key,
            content_id:this.content.id,
          };
          axios.post('/api/content/likes', data)
              .then(({data}) => {


                this.$store.commit('content/updateLikes', data.likes);

              })
              .catch(({response}) => {

              });
        },
        getLikes(){
          let data={content_id:this.content.id}
            axios.get('/api/content/likes', {params:data})
                .then(({data}) => {


                  this.$store.commit('content/updateLikes', data.likes);

                })
                .catch(({response}) => {

                });
        },
        getLikesByKey(key){
          for(let i in  this.$store.getters["content/getLikesById"](this.content.id))
          {
            if(this.$store.getters["content/getLikesById"](this.content.id)[i].key==key)
            {

              return this.$store.getters["content/getLikesById"](this.content.id)[i].total;
            }
          }
          return "";
        }

      },
      computed:{
        likes: function(){

          return this.$store.getters["content/getLikesById"];
        },
        isAuth(){
          return this.$store.getters["user/isAuth"];
        }
      }
    }
</script>
<style>

</style>
