//app.js
App({
  globalData:{
    userInfo: null,
    apiUrl: "http://114.55.102.33:8081/api/"
  },
  getUserInfo:function(cb){
    var that = this
    if(this.globalData.userInfo) {
      typeof cb == "function" && cb(this.globalData.userInfo)
    } else {
      //调用登录接口
      wx.login({
        success: function () {
          wx.getUserInfo({
            success: function (res) {
              that.globalData.userInfo = res.userInfo
              typeof cb == "function" && cb(that.globalData.userInfo)
            }
          })
        }
      })
    }
  },
})