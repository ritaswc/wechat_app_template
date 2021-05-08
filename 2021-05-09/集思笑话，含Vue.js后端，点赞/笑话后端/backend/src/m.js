// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import Mapp from './Mapp'
import router from './router/m'

Vue.filter('GetYMD', function (value) {
  var d = new Date(value)
  d = (' ' + d).split(' ')
  return d[2] + '-' + d[3] + '-' + d[4] + ' ' + d[5]
})

/* eslint-disable no-new */
new Vue({
  el: '#mapp',
  router,
  template: '<Mapp/>',
  components: { Mapp }
})
