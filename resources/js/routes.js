//Vue Routing
import VueRouter from 'vue-router'
import Welcome from './components/Welcome'
import Template from './components/Template'
import Register from './components/user/register'



export const router = new VueRouter({
      mode: 'history',
      routes: [
        {
          path: "/",
          name: "Home",
          component: Welcome
        },
        {
          path: "/template",
          name: "Template",
          component: Template
        },
        {
          path: "/register",
          name: "register",
          component: Register
        },]
});
