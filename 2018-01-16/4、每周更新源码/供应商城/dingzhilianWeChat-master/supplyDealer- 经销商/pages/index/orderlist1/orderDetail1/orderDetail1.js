//获取应用实例
var WxParse = require('wxParse/wxParse.js');
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
          var order = res.data.results;
          WxParse.wxParse('thumb', 'html', order[0].thumb, that, 5)
          that.setData({
            list: order
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
  //从渠道统计跳过来的订单由于页面深度已经到了5层,无法再次跳转,这里给一个提示
  goUpdate: function (e) {
    var id = e.currentTarget.dataset.id, state = e.currentTarget.dataset.state;
    wx.navigateTo({
      url: '/pages/index/orderlist1/orderDetail1/changeStatus1/changeStatus1?state=' + state + '&id=' + id,
      fail: function () {
        app.warning("请在印花订单列表中进行操作");
      }
    })
  },
  goExpress: function (e) {
    var no = e.currentTarget.dataset.expressno, code = e.currentTarget.dataset.expresscode;
    wx.navigateTo({
      url: '/pages/index/logistics/logistics?express_code=' + code + '&express_no=' + no,
      fail: function () {
        app.warning("请在印花订单列表中进行操作");
      }
    })
  },
  goInvoice: function (e) {
    var id = e.currentTarget.dataset.id;
    wx.navigateTo({
      url: '/pages/index/invoiceInfo/invoiceInfo?orderId=' + id,
      fail: function () {
        app.warning("请在印花订单列表中进行操作");
      }
    })
  },
})