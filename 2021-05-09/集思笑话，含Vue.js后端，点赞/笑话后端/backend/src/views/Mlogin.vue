<template>
<div class="page input js_show" style="background-color:#eee">
   <div class="page__bd">
      <div class="weui-cells">
        <div class="weui-cell">
                <div class="weui-cell__hd">
                     <label for="" class="weui-label">用户名</label>
                </div>
                <div class="weui-cell__bd">
                    <input class="weui-input" type="text" id="username" placeholder="输入用户名">
                </div>
        </div>
        <div class="weui-cell">
                <div class="weui-cell__hd">
                     <label for="" class="weui-label">密码</label>
                </div>
                <div class="weui-cell__bd">
                    <input class="weui-input" type="password" id="password" placeholder="输入密码">
                </div>
        </div>
       </div> 

      <div class="weui-cells__title">选择(登录、注册)</div>
      <div class="weui-cells">
        <div class="weui-cell weui-cell_select">
                <div class="weui-cell__bd">
                    <select class="weui-select" name="select1" id="select1">
                        <option selected="" value="1">登录</option>
                        <option value="2">注册</option>
                    </select>
                </div>
            </div>
       </div> <!-- weui-cells --> 
     </div> <!-- page__bd --> 

     <div class="weui-btn-area">
            <a class="weui-btn weui-btn_primary" @click="login" id="showTooltips">确定</a>
     </div>

</div>
</template>

<script>
import axios from 'axios'

export default {
  data () {
    return {
      jokes: null,
      jokeurl: '/login',
      jokeregister: '/register'
    }
  },
  created () {
  },

  methods: {
    login () {
      /* eslint-disable */      
      var select1 = $('#select1').val();
      var username1 = $('#username').val();
      var password1 = $('#password').val();
      /* eslint-enable */
      var that = this
      if (select1 === '1') {
        axios.post(this.jokeurl, {
          username: username1,
          password: password1
        })
          .then(function (response) {
            that.$router.push('/m')
          })
      } else if (select1 === '2') {
        axios.post(this.jokeregister, {
          username: username1,
          password: password1
        })
          .then(function (response) {
            that.autologin(username1, password1)
          })
      }
    },
    autologin (u, p) {
      var that = this
      axios.post(this.jokeurl, {
        username: u,
        password: p
      })
        .then(function (response) {
          that.$router.push('/m')
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
