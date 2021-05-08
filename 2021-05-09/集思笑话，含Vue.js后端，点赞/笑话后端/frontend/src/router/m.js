import Vue from 'vue'
import Router from 'vue-router'
import Mhome from 'views/Mhome'

Vue.use(Router)

export default new Router({
  routes: [
    {
      path: '/',
      name: 'mhome',
      component: Mhome
    }
  ]
})
