//app.js
App({
  onLaunch: function () {
    //调用API从本地缓存中获取数据
    wx.getNetworkType({
      success: function(res) {
        console.log(res.networkType);
        if(res.networkType == 'none') {
          wx.showModal({
            title: '警告⚠️',
            content: '请您连接网络后再使用本程序！',
            success: function(res) {
              if(res.confirm) {
                console.log('用户点击确定。');
              }else {
                console.log('用户点击取消。');
              }
            }
          });
        }
      }
    });
    var logs = wx.getStorageSync('logs') || []
    logs.unshift(Date.now())
    wx.setStorageSync('logs', logs)
  },
  getUserInfo:function(cb){
    var that = this
    if(this.globalData.userInfo){
      typeof cb == "function" && cb(this.globalData.userInfo)
    }else{
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
  globalData:{
    userInfo:null
  }
})