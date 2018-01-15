var app = getApp();

var WeatherService = require('../../utils/WeatherService');
var demoData = require('../../utils/data');

Page({
  data: {
    locInfo: {},
    info: {}
  },
  onShareAppMessage: function () {
    return {
      title: this.data.info.cityInfo.c3 + '天气',
      path: '/pages/index/index'
    }
  },
  onLoad: function () {
    var _this = this
    this.setData({
      info: app.globalData.info
    })
    wx.setNavigationBarTitle({
      title: this.data.info.cityInfo.c3 + '天气'
    })
  }
})