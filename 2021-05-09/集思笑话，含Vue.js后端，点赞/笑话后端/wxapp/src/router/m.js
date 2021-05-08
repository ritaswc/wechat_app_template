import Vue from 'vue'
import Router from 'vue-router'
import My from 'views/My'
import Mhome from 'views/Mhome'
import Mlogin from 'views/Mlogin'
import Mnewjoke from 'views/Mnewjoke'
import Mcrop from 'views/Mcrop'
import Mcomment from 'views/Mcomment'

Vue.use(Router)

export default new Router({
  mode: 'history',
  base: __dirname,
  routes: [
    { path: '/m/login', name: 'login', component: Mlogin },
    { path: '/m/my', name: 'my', component: My },
    { path: '/', name: 'home', component: Mhome },
    { path: '/m', name: 'home', component: Mhome },
    { path: '/m/comment', name: 'comment', component: Mcomment },
    { path: '/m/crop', name: 'crop', component: Mcrop },
    { path: '/m/newjoke', name: 'newjoke', component: Mnewjoke }
  ]
})
