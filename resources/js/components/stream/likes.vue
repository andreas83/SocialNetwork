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
import {mapGetters, mapActions} from 'vuex';
export default {
    name: "Likes",
    props:{
      content:false
    },
    data() {
        return{
          loaded:false,
          error:""
        }
      },
      async created(){

        await this.getLikes(this.content.id)
        this.loaded=true;

      },
      methods:{
        ...mapActions('likes', ['getLikes', 'createLike']),

        toggleComment(){

            this.$emit('toggleComment', this.content.id);

        },
        saveLike(key){
          let data={
            "key":key,
            "content_id":this.content.id,
          };
          this.createLike(data);
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
          getStoredLikes: 'likes/getLikes'
        }),

        isAuth(){
          return this.$store.getters["user/isAuth"];
        }
      }
    }
</script>
<style>

</style>
