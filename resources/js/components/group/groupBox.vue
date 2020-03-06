<template>

    <div id="groupBox">

      <div class="avatar" v-if="group.avatar" v-bind:style="{ 'background-image': 'url(' + getThumbnail(group.avatar, 150, 150) + ')' }" />
      {{group.avatar}}

      <h3>{{group.name}}</h3>



    </div>


</template>
<script>

    import {getThumbnail} from '../../helper/resize'
    export default {
    name:"GroupBox",
    props:{

      group_id:{
        default:false
      },
    },
    data() {

        return {
          group:{
            id:0,
            name:"",
            description:"",
            is_member:false,
            is_moderator:false
          }

        };
    },
    mounted(){

        await this.getGroup({id : this.$route.params.id});

    },
    methods: {
      getThumbnail,
        ...mapActions('groups', ['getGroup']),

    },
    computed:{


      isAuth(){
        return this.$store.getters["user/isAuth"];
      }
    },
    watch:{

    },
  }
</script>
