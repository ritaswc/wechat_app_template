// pages/index/basicInfo_statistics/basicInfo_statistics.js
var app = getApp();
Page({
  data: {
    imageCtx: app.globalData.imageCtx,
    hidden: false,
    emptyShow: false,
    deptOrder: []
  },
  onLoad: function (options) {
    var that = this, adminObj = app.globalData.adminObj;
    that.setData({
      dept_id: adminObj.dept_id,
      params: {
        phone: adminObj.phone,
        password: app.globalData.password,
        sessionId: adminObj.sessionId
      }
    })
    that.normal(that);
  },
  normal: function (that) {
    wx.request({
      url: app.globalData.requestUrl + "weixinMerchant/normalStatistic",
      data: that.data.params,
      success: function (res) {
        that.setData({
          hidden: true
        })
        if (res.data.code == '0') {
          var yesterOrder = res.data.mapResults.order_yester_old;
          var nowOrder = res.data.mapResults.order_now_new;
          var order = res.data.mapResults.order;
          var deptOrder = res.data.mapResults.deptOrder;
          that.setData({
            yesterOrder: yesterOrder,
            nowOrder: nowOrder,
            order: order
          })
          if (deptOrder != null && deptOrder.length > 0) {
            that.setData({
              deptOrder: deptOrder
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