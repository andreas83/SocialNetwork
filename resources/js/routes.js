//Vue Routing
import VueRouter from 'vue-router'
import Welcome from './components/Welcome'
import Template from './components/Template'
import Register from './components/user/register'
import Login from './components/user/login'

import User from './components/user/user'
import UserProfile from './components/user/userProfile'

import GroupOverview from './components/group/overview'
import GroupCreate from './components/group/new'
import Group from './components/group/show'

import Permalink from './components/Permalink'

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
          path: "/user/register",
          name: "register",
          component: Register
        },
        {
          path: "/user/login",
          name: "login",
          component: Login
        },
        {
          path: '/auth/:provider/callback',
          component: {
            template: '<div class="auth-component"></div>'
          }
        },
        {
          path: '/user/profile',
          name: "userProfile",
          component: UserProfile
        },
        {
          path: '/group/overview',
          name: "GroupOverview",
          component: GroupOverview
        },
        {
          path: '/group/create',
          name: "GroupCreate",
          component: GroupCreate
        },
        {
          path: '/group/:name/:id',
          name: "Group",
          component: Group
        },

        {
          path: '/permalink/:id',
          name: "permalink",
          component: Permalink
        },
        {
          path: '/:name',
          component: User,
          name:"user",

        }

      ]
});
