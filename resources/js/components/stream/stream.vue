<template>

  <div class="row">
    <div class="col-lg-12">
      <Editor></Editor>
    </div>
    <div class="col-lg-12">

      <div class="row card">
        <div class="col-lg-1  col-md-1">
        <picture>
          <img src="https://via.placeholder.com/50" />
        </picture>
        </div>
        <div class="col-lg-10  col-md-10">
          <header>The end is near :) </header>
          <author>
            Andreas B.
          </author>
          <date>2 min ago</date>
          <content>
              <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et </p>
          </content>

          <actions>
          </actions>
          <comments>
          </comments>
        </div>
      </div>
    </div>
  </div>
</template>
<script>

export default {
    name: "Strean",

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
