// pages/user/myorderdetals/myorderdetals.js
// pages/user/myorder/myorder.js

var base = getApp();
var common=require('../../../utils/common.js');
Page({
  data: {
    loaded: false,
    oid:0,
    myorder: {},
    prolist: [],
    oalist: [],
    goodprice: 0
  },
  onLoad: function (options) {
    // 页面初始化 options为页面跳转所带来的参数
    var _this = this;
    _this.setData({
      "oid":options.oid
    });
    this.initData();
  },
  initData: function () {
    var _this = this;
    base.get({ c: "UserCenter", m: "GetOrderInfoByOrderId", orderId: _this.data.oid}, function (d) {
      if (d.data.Status == "ok") {     
        var dat = d.data.Tag;
        var arr = [];
        var _goodsprice = 0, discount = parseFloat(dat.Order.DiscountAmount), _dis;
        for (var i = 0; i < dat.ListCake.length; i++) {
          dat.ListCake[i].Size = dat.ListCake[i].OType == '1' ? dat.ListCake[i].Remarks : dat.ListCake[i].Size;
          if (dat.ListCake[i].ProductName.indexOf("套餐") < 0) {
            arr.push(dat.ListCake[i]);
          }
          _goodsprice += dat.ListCake[i].Price * dat.ListCake[i].Num;
        }
        
        dat.Order.OrderTime = common.JsonDateToDateTimeString(dat.Order.OrderTime);
        dat.Order.DeliveryDate = common.JsonDateToDateString(dat.Order.DeliveryDate);
        if (dat.OaList.length > 0) {
          for (var i = 0; i < dat.OaList.length; i++) {
            discount += dat.OaList[i].DiscountAmount;
          }
          dat.Order.DiscountAmount = discount.toFixed(2);
        }
        _this.setData({
          "loaded": true,
          "myorder": dat.Order,
          "prolist": arr,
          "oalist": dat.OaList,
          "goodprice": _goodsprice.toFixed(2)
        });
      }
    })
  }

})