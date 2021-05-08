var amapFile = require('../../libs/amap-wx.js')
Page({
  data: {
    weather: {}
  },
  onLoad: function() {
    var that = this;
    var myAmapFun = new amapFile.AMapWX({key:'您的key'});
    myAmapFun.getWeather({
      success: function(data){
        that.setData({
          weather: data
        });
      },
      fail: function(info){
        wx.showModal({title:info.errMsg})
      }
    })
  }
})