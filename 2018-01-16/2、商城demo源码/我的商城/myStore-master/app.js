//app.js
App({
onLaunch: function() { 
  var that = this;
    // Do something initial when launch.
    //调用登录接口
      wx.login({
        success: function () {
          wx.getUserInfo({
            success: function (res) {
              that.globalData.userInfo = res.userInfo;
            }
          })
        }
      })
  },


  globalData:{
    userInfo:null,
    addressList:[],
    otherAddressInfo:null,
  }
})