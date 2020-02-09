<template>
  <div>
    <div class="row card">
            <div class="col-lg-2  col-md-2">
              <picture>
                <img :src="user.avatar" />
              </picture>
            </div>
            <div class="col-lg-8  col-md-8">
              <h2>  {{this.$route.params.name}}</h2>
              <p>{{user.bio}}</p>
            </div>

    </div>
  
    <stream :user_id="user.id"></stream>
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
