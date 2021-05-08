//app.js
var util = require('/utils/util.js')
App({
 getUserInfo:function(){
    var that = this
      //调用登录接口
      wx.login({
        success: function (LoginRes) {
          //获取openID
          var loginUrl = "https://api.weixin.qq.com/sns/jscode2session?";
          var dataInfo = {
            appid: "wx12e0d9958c5b3bb6",
            secret: "30356ac2c536e99bbfe1ad2cd2a40963",
            js_code: LoginRes.code,
            grant_type: "authorization_code",
          }
          util.HttpGet(loginUrl, dataInfo, function (response) {
            try {
                wx.setStorageSync('openid', response.openid);
            } catch (e) {    
            }
          });
          //获取基础信息
          wx.getUserInfo({
            success: function (res) {
              console.log(res);
              that.globalData.userInfo = res.userInfo
            }
          })
        }
      })
    
  },


  onLaunch: function () {
   this.getUserInfo();
  },
 
  globalData:{
    userInfo:null
  }
})