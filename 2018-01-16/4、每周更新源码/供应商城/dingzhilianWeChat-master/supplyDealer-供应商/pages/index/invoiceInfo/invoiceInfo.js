var app = getApp();

Page({
  data: {
    hidden: false,
    list: []
  },
  onLoad: function (option) {
    var that = this;
    var params = {}, adminObj = app.globalData.adminObj;
    params.orderId = option.orderId;
    params.phone = adminObj.phone;
    params.password = app.globalData.password;
    params.sessionId = adminObj.sessionId;
    wx.request({
      url: app.globalData.requestUrl + 'weixinMerchant/getOrderInvoice',
      data: params,
      success: function (res) {
        that.setData({
          hidden: true
        })
        if (res.data.code == '0') {
          that.setData({
            list: res.data.results
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