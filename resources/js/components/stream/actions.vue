<template>
  <div class="action">
    <button v-if="content.has_comment=='true' && content.is_comment=='false'" class="icon-comment" v-on:click="toggleComment"></button>
    <button v-if="content.has_comment=='false' && content.is_comment=='false'" class="icon-comment-empty" v-on:click="toggleComment"/>

    <button class="icon-heart"> 3 </button>
    <button class="icon-happy"/>
    <button class="icon-wink"/>

    <button class="icon-like"/>
    <button class="icon-devil"/>
    <button class="icon-coffee"/>
    <button class="icon-sunglasses"/>
    <button class="icon-displeased"/>
    <button class="icon-beer"/>
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

      },
      methods:{
        toggleComment(){

            this.$emit('toggleComment', this.content.id);

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
