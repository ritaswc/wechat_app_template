var app = getApp();
Page({
  data: {
    hidden: false,
    colors: [],
    style: '',
    total: 0
  },
  onLoad: function (option) {
    var that = this, style = option.style;
    that.setData({
      style: style
    })
    that.colors(that);
  },
  colors: function (that) {
    var that = this, params = {}, adminObj = app.globalData.adminObj;
    params.style = that.data.style;
    params.phone = adminObj.phone;
    params.password = app.globalData.password;
    params.sessionId = adminObj.sessionId;
    wx.request({
      url: app.globalData.requestUrl + "weixinMerchant/sellStatisticForStyleColor",
      data: params,
      success: function (res) {
        that.setData({
          hidden: true
        })
        if (res.data.code == '0') {
          var map = res.data.mapResults;
          that.setData({
            colors: map.stats,
            total: map.total,
          })
        } else {
          app.noLogin(res.data.msg);
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