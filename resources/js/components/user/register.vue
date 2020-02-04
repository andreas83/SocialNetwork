<template>
  <div>
    <form>
      <div class="form-field">
        <label for="name">{{$t('form.name')}}</label>
        <input id="name" autocomplete="nickname" type="text" placeholder=" " v-model="name" />

        <p class="error"  v-for="error in this.error.name"> {{error}}</p>

      </div>
      <div class="form-field">
        <label for="test">{{$t('form.password')}}</label>
        <input id="test" autocomplete="new-password" type="password" placeholder="" v-model="password"  />
        <p class="error"  v-for="error in this.error.password"> {{error}}</p>
      </div>
      <div class="form-field">
        <label for="mail">{{$t('form.email')}}</label>
        <input id="mail" autocomplete="email" type="email" name="test" placeholder="email " v-model="email"/>
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
                })
                .catch(({response}) => {
                  this.show=true;
                  this.error=response.data.errors;
                });
        },

    }
}
</script>
