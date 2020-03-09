<template>

    <div id="groupBox">

      <div class="avatar" v-if="group.avatar" v-bind:style="{ 'background-image': 'url(' + getThumbnail(group.avatar, 150, 150) + ')' }" />
      {{group.avatar}}

      <p>{{group.description}}</p>



      <button>{{$t("form.group.changePicture")}}</button>
      <button>{{$t("form.group.changeDescription")}}</button>
      <button>{{$t("form.group.join")}}</button>
      <button>{{$t("form.group.leave")}}</button>



    </div>


</template>
<script>
    import {mapGetters, mapActions} from 'vuex';
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
            background:"",
            description:"",
            is_member:false,
            is_moderator:false
          }

        };
    },
    mounted(){

        this.getGroup({id : this.$route.params.id});

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
