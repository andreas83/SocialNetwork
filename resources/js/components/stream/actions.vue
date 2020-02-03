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
import {mapGetters} from 'vuex';
export default {
    name: "Actions",
    props:{
      content:false
    },
    data() {
        return{
          loaded:false,
          error:""
        }
      },
      created(){
        this.getLikes();

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
                  this.loaded=true;
                })
                .catch(({response}) => {

                });
        },
        getLikesByKey(key){

            if(!this.loaded)
              return false;

            var data=this.getStoredLikes;
            var content_id=this.content.id;
            const index = data.findIndex(item => item.content_id == content_id);

            for(let i in data[index].likes)
            {

                if(data[index].likes[i].key==key)
                {
                  return data[index].likes[i].total;
                }

            }
           return "";
        }

      },
      computed:{

        ...mapGetters({
          getStoredLikes: 'content/getLikes'
        }),

        isAuth(){
          return this.$store.getters["user/isAuth"];
        }
      }
    }
</script>
<style>

</style>
