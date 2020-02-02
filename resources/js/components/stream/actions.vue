<template>
  <div class="action">
    <button class="icon-comment" v-on:click="toggleComment"/>
    <button class="icon-comment-empty"/>

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

    data() {
        return{

          error:""
        }
      },
      mounted(){

      },
      methods:{
        toggleComment(){

            this.$emit('toggleComment', 'true');

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
