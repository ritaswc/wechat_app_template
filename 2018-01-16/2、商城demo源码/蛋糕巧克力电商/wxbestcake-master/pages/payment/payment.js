// pages/payment/payment.js
var base = getApp();
Page({
  data: {
    loaded: false,
    oid: "1703101349147978",
    myorder: {},
    prolist: [],
    oalist: [],
    goodprice: 0,
    paymentList: [
      { "id": "0", "name": "微信支付" },
      { "id": "1", "name": "货到付款" },
      { "id": "2", "name": "吉致币" }
    ],
    selid: "0",
    jzb: 0
  },
  onLoad: function (options) {
    // 页面初始化 options为页面跳转所带来的参数
    var _this = this;
    _this.setData({
      "oid": options.oid,
      jzb: base.user.jzb
    });

    wx.setNavigationBarTitle({
      title: '支付方式'
    })
    _this.initData();
  },
  onReady: function () {
    // 页面渲染完成
  },
  onShow: function () {
    // 页面显示
  },
  onHide: function () {
    // 页面隐藏
  },
  onUnload: function () {
    // 页面关闭
  },
  initData: function () {
    var _this = this;
    base.get({ c: "UserCenter", m: "GetOrderInfoByOrderId", orderId: _this.data.oid }, function (d) {
      if (d.data.Status == "ok") {
        var dat = d.data.Tag;
        var arr = [];
        var _goodsprice = 0, discount = parseFloat(dat.Order.DiscountAmount);
        for (var i = 0; i < dat.ListCake.length; i++) {
          _goodsprice += dat.ListCake[i].Price * dat.ListCake[i].Num;
        }
        if (dat.OaList.length > 0) {
          for (var i = 0; i < dat.OaList.length; i++) {
            discount += dat.OaList[i].DiscountAmount;
          }
          dat.Order.DiscountAmount = discount.toFixed(2);
        }
        var n = "0";
        if (dat.Order.Payment == '货到付款') {
          n = "1";
        }
        else if (dat.Order.Payment == '极致币') {
          n = "2";
        }
        _this.setData({
          "loaded": true,
          "myorder": dat.Order,
          "prolist": arr,
          "oalist": dat.OaList,
          "goodprice": _goodsprice.toFixed(2),
          "selid": n
        });
      }
    })
  },
  paymentselected: function (e) {
    var id = e.currentTarget.dataset.aid;
    this.setData({ selid: id });
  },
  gopay: function () {
    var _this = this;
    var arr = this.data.paymentList;
    var payment = "";
    for (var i = 0; i < arr.length; i++) {
      if (arr[i].id == _this.data.selid) {
        payment = arr[i].name;
        break;
      }
    }
    var obj = {};
    obj.OrderId = this.data.oid;
    obj.Bank = payment == "吉致币" ? "极致币" : payment;
    base.post({
      c: "OrderCenter", m: "AddPayment", p: JSON.stringify(obj)
    }, function (d) {
      console.log(d)
      var dt = d.data;
      if (dt.Status == "ok") {
        if (payment == "微信支付") {

        }
        else {
          wx.redirectTo({
            url: "../success/success?oid=" + _this.data.oid
          })
        }
      }
      else
      {
         wx.showModal({
        showCancel: false,
        title: '',
        content: dt.Msg
      })
      }
    })
  }
})