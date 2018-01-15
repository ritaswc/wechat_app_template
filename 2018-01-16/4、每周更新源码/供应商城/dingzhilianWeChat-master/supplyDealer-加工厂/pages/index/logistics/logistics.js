var app = getApp();

Page({
  data: {
    imageCtx: app.globalData.imageCtx,
    emptyShow: false,
    hidden: false,
    list: []
  },
  onLoad: function (option) {
    console.log(option.express_code + ":" + option.express_no)
    var that = this;
    wx.request({
      url: app.globalData.requestUrl + 'express',
      data: {
        'type': option.express_code,
        postid: option.express_no
      },
      success: function (res) {
        that.setData({
          hidden: true
        })
        if (res.data.code == '0') {
          that.setData({
            list: res.data.results
          })
        } else {
          that.setData({
            emptyShow: true
          })
        }
      },
      fail: function (res) {
        that.setData({
          hidden: true
        })
        app.warning("服务器无响应");
      }
    })
  }
})