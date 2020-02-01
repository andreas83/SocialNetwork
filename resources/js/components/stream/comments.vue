<template>

  <div class="row">
    <Editor is-comment=true></Editor>
  
    <comment>
      <author>
        Andreas B.
      </author>
      <date>2 min ago</date>
      <content>
          <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et </p>
      </content>
    </comment>
    <comment>
      <author>
        Andreas B.
      </author>
      <date>2 min ago</date>
      <content>
          <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et </p>
      </content>
    </comment>
  </div>
</template>
<script>

export default {
    name: "Comments",

    data() {
        return{

          error:""
        }
      },
      mounted(){

      },
      methods:{

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
