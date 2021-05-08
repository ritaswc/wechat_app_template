//index.js
//获取应用实例
var app = getApp()
Page({
  data: {
    motto: '会议详情',
    currentDate: new Date(),
    info: {}
  },
  //事件处理函数
  onLoad: function(options) {
      var that = this;
    // 获取天气信息
    wx.request({
        url: 'https://liuanchen.com/w/meeting/' + options.id,
        success: function(res) {
            that.setData({
                info: res.data
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
