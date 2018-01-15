//index.js
//获取应用实例
var app = getApp()
var WeatherService = require('../../utils/WeatherService');

Page({
  data: {
    motto: '欢迎来到hopper天气...',
    userInfo: {}
  },
  //事件处理函数
  bindViewTap: function () {
    wx.navigateTo({
      url: '../logs/logs'
    })
  },
  onLoad: function () {
    console.log('onLoad')
    var that = this
    //调用应用实例的方法获取全局数据
    app.getUserInfo(function (userInfo) {
      //更新数据
      that.setData({
        userInfo: userInfo
      })
    });

    wx.getLocation({
      type: 'wgs84', // 默认为 wgs84 返回 gps 坐标，gcj02 返回可用于 wx.openLocation 的坐标
      success: function (res) {
        // success
        console.dir(res)
        app.globalData.locInfo = res;
        WeatherService.getByGps(app.globalData.locInfo.latitude, app.globalData.locInfo.longitude).then((data) => {
          app.globalData.info = data.showapi_res_body;
          wx.redirectTo({
            url: '../main/main',
            success: function (res) {
              // success
            },
            fail: function () {
              // fail
            },
            complete: function () {
              // complete
            }
          })
        }).catch((err) => {

        });

      },
      fail: function () {
        // fail
      },
      complete: function () {
        // complete
      }
    })

  }
})
