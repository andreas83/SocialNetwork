<template>

  <div class="row">

    <div class="col-lg-12" >
      <stream :content_id="$route.params.id"></stream>
    </div>




  </div>
</template>
<script>

export default {
    name: "permalink",

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
