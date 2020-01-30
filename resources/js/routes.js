//Vue Routing
import VueRouter from 'vue-router'

import Layout from './components/Layout'

export const router = new VueRouter({
      mode: 'history',
      routes: [
        {
          path: "/",
          name: "Home",
          component: Layout
        }]
});
