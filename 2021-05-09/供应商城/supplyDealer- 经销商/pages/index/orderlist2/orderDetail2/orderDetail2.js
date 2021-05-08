//获取应用实例
var app = getApp();

Page({
  data: {
    imageCtx: app.globalData.imageCtx,
    list: [],
    hidden: false
  },
  onLoad: function (option) {
    var that = this;
    var params = {}, adminObj = app.globalData.adminObj;
    params.orderNo = option.orderNo;
    params.phone = adminObj.phone;
    params.password = app.globalData.password;
    params.sessionId = adminObj.sessionId;

    wx.request({
      url: app.globalData.requestUrl + 'weixinMerchant/getOrderDetail',
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
  },
  goUpdate: function (e) {
    var id = e.currentTarget.dataset.id, state = e.currentTarget.dataset.state;
    wx.navigateTo({
      url: '/pages/index/orderlist2/orderDetail2/changeStatus2/changeStatus2?state=' + state + '&id=' + id
    })
  },
  goExpress: function (e) {
    var no = e.currentTarget.dataset.expressno, code = e.currentTarget.dataset.expresscode;
    wx.navigateTo({
      url: '/pages/index/logistics/logistics?express_code=' + code + '&express_no=' + no
    })
  },
  goInvoice: function (e) {
    var id = e.currentTarget.dataset.id;
    wx.navigateTo({
      url: '/pages/index/invoiceInfo/invoiceInfo?orderId=' + id
    })
  },
})