<template>
<div class="page preview js_show" style="background-color:#eee">
       <div class="weui-cells">
           <div class="page__bd page__bd_spacing" style="height:60px;">
                  <div class="weui-flex js_category">
                    <a href="http://jsjoke.net/m"> 
                     <p class="weui-flex__item" style="margin:15px;"> < </p>
                    </a>
                    <p class="weui-flex__item" style="margin:15px;"></p>
                    <a href="/m/newjoke">
                     <img src="/static/camera.png" style="width:40px;margin:15px;" alt="">
                    </a>
                </div>
           </div>
           <div class="weui-cell">
                <div class="weui-cell__hd" style="position: relative;margin-right: 10px;">
                    <img v-bind:src="userinfo.avatar||'/static/default-img.png'" style="width: 50px;display: block" @click="crop">
                </div>
                <div class="weui-cell__bd">
                    <p>{{userinfo.username}}</p>
                    <p style="font-size: 13px;color: #888888;" @click="changenick">{{userinfo.nickname||'添加昵称'}}</p>
                </div>
            </div>
            <div class="weui-cell weui-cell_vcode" style="display:none;" id="nickname">
                <div class="weui-cell__hd">
                    <label class="weui-label">改变昵称</label>
                </div>
                <div class="weui-cell__bd">
                    <input class="weui-input" type="text" id="nickinput" placeholder="请输入昵称">
                </div>
                <div class="weui-cell__ft">
                    <button class="weui-vcode-btn" @click="donenick">确认修改</button>
                </div>
            </div>
       </div>
 
</div>
</template>

<script>
import axios from 'axios'

export default {
  data () {
    return {
      userinfo: {},
      userurl: '/my',
      jokeurl: '/jokes'
    }
  },
  created () {
    this.getMy()
  },

  methods: {
    crop () {
      this.$router.push('/m/crop')
    },
    getMy () {
      var that = this
      axios.get(this.userurl)
        .then(function (response) {
          that.userinfo = response.data
        })
    },
    changenick () {
      /* eslint-disable */
      var nickname1 = $('#nickname');
      /* eslint-enable */
      nickname1.show()
    },
    donenick () {
      /* eslint-disable */
      var nickname1 = $('#nickname');
      var nickinput1 = $('#nickinput').val();
      /* eslint-enable */
      nickname1.hide()
      this.userinfo.nickname = nickinput1
      axios.post(this.userurl, this.userinfo)
        .then(function (response) {
          window.location.reload()
        })
    },
    getJokes () {
      var that = this
      axios.get(this.jokeurl + '?sort=-_id')
        .then(function (response) {
          that.jokes = response.data
        })
    }
  },
  components: {
  }
}
</script>
<style>
div > img {width:50%;}
a {text-decoration:none;}
</style>
