//Vue Routing
import VueRouter from 'vue-router'
import Welcome from './components/Welcome'
import Template from './components/Template'
import Register from './components/user/register'

import User from './components/user/user'
import UserProfile from './components/user/userProfile'
import UserPosts from './components/user/userPosts'

export const router = new VueRouter({
      mode: 'history',
      routes: [
        {
          path: "/",
          name: "home",
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
        },

        {
          path: '/:name/',
          component: User,
          name:"user",
          children: [
            {
              // UserProfile will be rendered inside User's <router-view>
              // when /user/:id/profile is matched
              path: 'profile',
              component: UserProfile
            },
            {
              // UserPosts will be rendered inside User's <router-view>
              // when /user/:id/posts is matched
              path: 'posts',
              component: UserPosts
            }
          ]
        }

      ]
});
