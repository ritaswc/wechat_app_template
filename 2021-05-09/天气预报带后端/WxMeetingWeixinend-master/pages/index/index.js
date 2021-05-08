//index.js
//获取应用实例
var app = getApp()
Page({
  data: {
    motto: 'Hello World',
    userInfo: {},
    weatherInfo: {}
  },
  //事件处理函数
  bindViewTap: function() {
    wx.navigateTo({
      url: '../logs/logs'
    })
  },
  onLoad: function () {
    console.log('onLoad')
    var that = this
    //调用应用实例的方法获取全局数据
    app.getUserInfo(function(userInfo){
      //更新数据
      that.setData({
        userInfo:userInfo
      })
    })

  // 获取天气信息
  wx.request({
    url: 'https://liuanchen.com/w/weather',
    success: function(res) {
      that.setData({
            weatherInfo: res.data
          })
    },
    fail: function(error) {
      that.setData({
            errMsg: error.errMsg
          })
    }
  })
  }
})
