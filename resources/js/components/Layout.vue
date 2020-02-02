<template>
  <div>
    <nav>
      <h1>SocialNetwort - <small>bold statement</small></h1>
      <div v-on:click="toggleLanguageDropdown" class="language-select">
        <ul>
          <li>Lang</li>
          <ul v-if="showLanguageDropdown">
            <li v-on:click="switchLang('de')" >DE</li>
            <li v-on:click="switchLang('en')">EN</li>
          </ul>
        </ul>

      </div>
    </nav>

    <div class="container">
      <router-view>
      </router-view>
    </div>
  </div>
</template>
<script>
export default {
    data() {
        return{

          showLanguageDropdown:false
        }
      },
      mounted(){

      },
      methods:{
        switchLang(lang){
            localStorage.setItem('lang', lang);
            this.$i18n.locale=lang;
        },
        toggleLanguageDropdown(){
          if(this.showLanguageDropdown)
          {
            this.showLanguageDropdown=false
          }else {
            this.showLanguageDropdown=true;
          }
        }
      },

    }
</script>
<style>

</style>
