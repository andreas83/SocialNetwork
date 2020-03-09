<template>

    <div id="userBox">

      <div class="avatar" v-if="user.avatar" v-bind:style="{ 'background-image': 'url(' + getThumbnail(user.avatar, 150, 150) + ')' }" />


      <h3>{{user.name}}</h3>
      <p>{{user.bio}}</p>

      <div></div>
    </div>


</template>
<script>

    import {getThumbnail} from '../../helper/resize'
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
      getThumbnail,
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
