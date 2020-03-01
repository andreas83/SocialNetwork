<template>

    <div id="userBox">

      <div class="avatar" v-bind:style="{ 'background-image': 'url(' + user.avatar + ')' }" />


      <h3>{{user.name}}</h3>
      <p>{{user.bio}}</p>


    </div>


</template>
<script>


    export default {
    name:"User",
    props:{

      user_id:{
        default:false
      },
    },
    data() {

        return {
          user:{
            id:0,
            bio:"",
            avatar:""
          }

        };
    },
    mounted(){

      this.getUser();

    },
    methods: {

      getUser(){
        //  this.$route.params.username

          axios.get('/api/user/public/?id='+this.user_id)
              .then(({data}) => {
                this.user=data;


              })
              .catch(({response}) => {

              });
      },

    },
    computed:{


      isAuth(){
        return this.$store.getters["user/isAuth"];
      }
    },
    watch:{

      user_id(){

         this.getUser();
      }
    },
  }
</script>
