var app = getApp();
Page({
  data: {
    imageCtx: app.globalData.imageCtx,
    emptyShow: false,
    hidden: false,
    style10: [],
    total: 0
  },
  onLoad: function () {
    var that = this;
    that.style(that);
  },
  goColor: function (e) {
    var style = e.currentTarget.dataset.style;
    wx.navigateTo({
      url: '/pages/index/styleSell_statistics/styleColor/styleColor?style=' + style
    })
  },
  style: function (that) {
    var that = this, adminObj = app.globalData.adminObj;
    wx.request({
      url: app.globalData.requestUrl + "weixinMerchant/sellStatisticForStyle",
      data: {
        phone: adminObj.phone,
        password: app.globalData.password,
        sessionId: adminObj.sessionId
      },
      success: function (res) {
        that.setData({
          hidden: true
        })
        if (res.data.code == '0') {
          var map = res.data.mapResults, style10 = [];
          var style = map.stats;
          if (style && style.length > 0) {
            for (var s in style) {
              style10.push(style[s]);
              if (s == 9) {//只获取前10的款式
                break;
              }
            }
            that.setData({
              style10: style10,
              total: map.total,
            })
          } else {
            that.setData({
              emptyShow: true
            })
          }
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