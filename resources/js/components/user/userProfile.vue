<template>
  <div>
    user

  </div>

</template>
<script>


    export default {
    name:"UserProfile",
    data() {

        return {


        };
    },
    mounted(){
      this.$route.params.username
    },

    methods: {



    }
}
</script>
