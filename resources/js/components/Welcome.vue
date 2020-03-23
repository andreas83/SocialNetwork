<template>

  <div class="row">

    <div v-if="isAuth" class="col-lg-12" >
      <stream></stream>
    </div>

    <div v-if="isAuth==false" class="col-lg-6 col-md-12">
      <Login></Login>
    </div>
    <div v-if="isAuth==false" class="col-lg-6 col-md-12">
      <Register></Register>
    </div>


  </div>
</template>
<script>
import Register from './user/register'
export default {
    name: "Welcome",

    data() {
        return{

          error:""
        }
      },
      mounted(){
        window.scroll(0,0);
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
