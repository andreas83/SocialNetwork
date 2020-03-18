<template>
  <div class="action">
    <div class="row">
      <div v-if="content.is_comment!='true'" class="col-lg-4 ">

        <button v-if="showLikes!=true && content.has_comment=='true' && content.is_comment=='false'" class="icon-comment" v-on:click="toggleComment"> {{$t('Comment')}} {{content.comments}}</button>
        <button v-if="showLikes!=true && content.has_comment=='false' && content.is_comment=='false'" class="icon-comment-empty" v-on:click="toggleComment"> {{$t('Comment')}} {{content.comments}}</button>
      </div>
      <div v-bind:class=likeCssClass>
        <button v-if="showLikes!=true " @click="showLikes=true" class="icon-heart">{{getLikesByKey("heart")}} Likes</button>
        <div  v-if="showLikes">
          <button @click="saveLike('heart')" class="icon-heart">{{getLikesByKey("heart")}}</button>
          <button @click="saveLike('happy')" class="icon-happy">{{getLikesByKey("happy")}}</button>
          <button @click="saveLike('wink')" class="icon-wink">{{getLikesByKey("wink")}}</button>
          <button @click="saveLike('like')" class="icon-like">{{getLikesByKey("like")}}</button>
          <button @click="saveLike('devil')"  class="icon-devil">{{getLikesByKey("devil")}}</button>
          <button @click="saveLike('coffee')"  class="icon-coffee">{{getLikesByKey("coffee")}}</button>
          <button @click="saveLike('sunglasses')"  class="icon-sunglasses">{{getLikesByKey("sunglasses")}}</button>
          <button @click="saveLike('displeased')"  class="icon-displeased">{{getLikesByKey("displeased")}}</button>
          <button @click="saveLike('beer')"  class="icon-beer">{{getLikesByKey("beer")}}</button>
          <button class="close" @click="showLikes=false ">x Close</button>
        </div>
      </div>
      <div v-bind:class=reshareCssClass>

        <button v-if="showLikes!=true " @click="reshare(content.id)"  class="icon-reply-all">Reshare</button>
      </div>
    </div>
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
          showLikes:false,
          loaded:false,
          error:"",
          likeCssClass:"col-lg-4",
          reshareCssClass: "col-lg-4"
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
        reshare(id)
        {
          this.$emit('reshareContent', id);
        },
        saveLike(key){

          if(!this.isAuth)
          {
            this.$router.push('/');
          }

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
      watch:{
        showLikes(val){
          if(val==true)
          {
            this.likeCssClass="col-lg-12";
          }
          else {
            this.likeCssClass="col-lg-4";
          }
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
