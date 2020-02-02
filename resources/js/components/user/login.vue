<template>
  <div>
    <form>
      <div class="form-field">
        <label for="login-mail">{{$t('form.email')}}</label>
        <input id="login-mail" autocomplete="email" type="email" name="test" placeholder="email " v-model="email"/>
        <p class="error"  v-for="error in this.error.email"> {{error}}</p>
      </div>
      <div class="form-field">
        <label for="login-password">{{$t('form.password')}}</label>
        <input id="login-password" autocomplete="new-password" type="password" placeholder="" v-model="password"  />
        <p class="error"  v-for="error in this.error.password"> {{error}}</p>
      </div>

      <div class="form-field">
        <button class="btn default" v-on:click="login" value="default">{{$t('form.login')}}</button>

      </div>
    </form>

  </div>

</template>
<script>


    export default {
    name:"Login",
    data() {

        return {

            error:"",

            email: '',
            password: '',
            message:'',
            show:false,
        };
    },

    methods: {

        login(e) {
          e.preventDefault();
            let token = document.head.querySelector('meta[name="csrf-token"]');
            let data = {

                email: this.email,
                password: this.password

            };


            axios.post('/api/login', data)
                .then(({data}) => {

                    this.$store.commit('user/setUser', data.user);
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
                    this.$router.push('/');

                })
                .catch(({response}) => {
                  this.show=true;
                  this.error=response.data.errors;
                });
        },

    }
}
</script>
