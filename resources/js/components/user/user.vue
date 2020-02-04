<template>
  <div class="row">

    {{this.$route.params.name}}
    {{user.created_at}}


  </div>

</template>
<script>


    export default {
    name:"User",
    data() {

        return {
          user:{}

        };
    },
    mounted(){

      this.getUser();

    },
    methods: {

      getUser(){
        //  this.$route.params.username

          axios.get('/api/user/'+this.$route.params.name)
              .then(({data}) => {
                this.user=data;
                console.log(data);
                // for(var i=0, length= data.content.data.length; i < length; i++)
                // {
                //
                //   data.content.data[i].show_comment=false;
                // }
                //
                // this.$store.commit('content/setContent', data.content.data);

              })
              .catch(({response}) => {

              });
      },

    },
    computed:{
      content(){
        return this.$store.getters["content/getContent"];
      },

      isAuth(){
        return this.$store.getters["user/isAuth"];
      }
    }
  }
</script>
