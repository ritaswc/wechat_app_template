// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import 'admin-lte/bootstrap/js/bootstrap'
import 'admin-lte/dist/js/app'
import Vue from 'vue'
import App from './App'
import router from './router'
import axios from 'axios'

require('admin-lte/dist/css/AdminLTE.css')
require('admin-lte/dist/css/skins/skin-blue.min.css')

// for side menus
// add a menu entry, need add a link entry by routers.js
var menuvue = new Vue({
  el: '#menu_main',
  data: {
    userinfo: { username: 'Joker', avatar: '/static/default-img.png' },
    menus: [
      { title: 'MENU', vclass: 'header' },
      { title: 'Joke管理',
        url: '/admin/myjokes',
        vclass: 'treeview',
        submenus: [
          { title: '我的Joke', url: '/admin/myjokes' },
          { title: '添加一个Joke', url: '/admin/addjoke' }
        ]
      },
      { title: '我的信息', url: '/admin/my' }
    ]
  }
}).$mount('#menu_main')

axios.get('/my')
  .then(function (response) {
    menuvue.$options.data().userinfo = response.data
    if (!response.data.avatar) {
      menuvue.$options.data().userinfo.avatar = '/static/default-img.png'
    }
  })

window.menuvue = menuvue

/* eslint-disable no-new */
new Vue({
  el: '#app',
  router,
  template: '<App/>',
  components: { App }
})
