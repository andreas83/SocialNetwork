<template>

  <div class="row-0">
      <div contenteditable="true" class="editor" v-bind:class="{ 'comment': isComment }"></div>
      <button class="default icon-picture"/>
      <button class="btn default" v-if="!isComment"> {{$t('Save')}}</button>
      <button class="btn default" v-if="isComment"> {{$t('Comment')}}</button>
  </div>
</template>
<script>

export default {
    name: "Editor",
    props: ['isComment'],
    data() {
        return{

          error:""
        }
      },
      mounted(){

      },
      methods:{
        getClass(){
          return (isComment ? "x" : "y");
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
