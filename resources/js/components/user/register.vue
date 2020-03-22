<template>
  <div>
    <form>
      <div class="form-field">
        <input id="name" autocomplete="nickname" type="text"
        v-bind:placeholder="$t('form.name')" v-model="name" />

        <p class="error"  v-for="error in this.error.name"> {{error}}</p>

      </div>
      <div class="form-field">

        <input v-bind:placeholder="$t('form.password')"  autocomplete="new-password" type="password" v-model="password"  />
        <p class="error"  v-for="error in this.error.password"> {{error}}</p>
      </div>
      <div class="form-field">
        <input id="mail" autocomplete="email" type="email"   v-bind:placeholder="$t('form.email')"  v-model="email"/>
        <p class="error"  v-for="error in this.error.email"> {{error}}</p>
      </div>
      <div class="form-field">
        <button class="btn default" v-on:click="register" value="default">{{$t('form.register')}}</button>

      </div>
    </form>

  </div>

</template>
<script>


    export default {
    name:"register",
    data() {

        return {

            error:"",
            name: '',
            email: '',
            password: '',
            message:'',
            show:false,
        };
    },

    methods: {



        back() {
          this.$router.push('/Login');
        },
        register(e) {
          e.preventDefault();
            let data = {
                name: this.name,
                email: this.email,
                password: this.password
            };

            axios.post('/api/register', data)
                .then(({data}) => {
                  console.log(data);
                  this.$store.commit('user/setUser', data);
                  this.$store.commit('user/setAuth', true);
                  localStorage.setItem('token', data.user.api_token);
                  axios.interceptors.request.use(
                    (config) => {
                      config.headers['Authorization'] = "Bearer "+data.user.api_token;
                      return config;
                    },

                    (error) => {
                      return Promise.reject(error);
                    }
                  );
                })
                .catch(({response}) => {
                  this.show=true;
                  this.error=response.data.errors;
                });
        },

    }
}
</script>
