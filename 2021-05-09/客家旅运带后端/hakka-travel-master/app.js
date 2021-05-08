//app.js
var dateUtil = require('./utils/date.js');
App({
  onLaunch: function () {
    // 监听小程序初始化,当小程序初始化完成时，会触发 onLaunch（全局只触发一次）
  },
  onShow: function() {
    // 监听小程序显示,当小程序启动，或从后台进入前台显示，会触发 onShow
  },
  onHide: function() {
    // 监听小程序隐藏,当小程序从前台进入后台，会触发 onHide
  },
  onError: function(msg) {
    // 错误监听函数,当小程序发生脚本错误，或者 api 调用失败时，会触发 onError 并带上错误信息
    console.log("error message: " + msg)
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
    startCity: '请选择出发地点',
    endCity: '请选择到达地点',
    startStation: '',
    endStation: '',
    date: dateUtil.getToday()
  }
})