
/**
 * app.js
 * App() 函数用来注册一个小程序。接受一个 object 参数，其指定小程序的生命周期函数等。
 * 
 * 注意:
 * 1. App() 必须在 app.js 中注册，且不能注册多个。 
 * 2. 不要在定义于 App() 内的函数中调用 getApp() ，使用 this 就可以拿到 app 实例。
 * 3. 不要在 onLaunch 的时候调用 getCurrentPage()，此时 page 还没有生成。
 * 4. 通过 getApp() 获取实例之后，不要私自调用生命周期函数。
 * 
 */
App({

  //生命周期函数1--监听小程序初始化, 当小程序初始化完成时，会触发onLaunch（全局只触发一次）
  onLaunch: function () {
    //调用API从本地缓存中获取数据
    var logs = wx.getStorageSync('logs') || []
    logs.unshift(Date.now())
    wx.setStorageSync('logs', logs)
  },

  //生命周期函数2--监听小程序显示, 当小程序启动，或从后台进入前台显示，会触发onShow
  onShow: function() {

  },

  //生命周期函数3--监听小程序隐藏, 当小程序从前台进入后台，会触发onHide
  onHide: function() {

  },

  getUserInfo: function (cb) {
    var that = this
    if (this.globalData.userInfo) {
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

  globalData: {
    userInfo: null
  }
})