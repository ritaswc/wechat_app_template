var app          = getApp();
var common       = require('../../util/util.js');
var order_detail = {
  "data": [
    {
      "buyNums": 1,
      "orderList": [
        {
          "buyNum": 2,
          "goodsImg": "../../image/test2.jpg",
          "goodsName": "列表商品",
          "goodsStandardDes": "颜色：红色  尺码：s",
          "sellPrice": 299,
          "shopId": 1,
        }
      ],
      "orderNum": "abc2511483687801946",
      "orderStatusStr": "已完成",
      "orderTime": 1483687801000,
      "totalPrice": 0
    },
    {
      "buyNums": 1,
      "orderList": [
        {
          "buyNum": 2,
          "goodsImg": "../../image/test3.jpg",
          "goodsName": "列表商品",
          "goodsStandardDes": "颜色：红色  尺码：s",
          "sellPrice": 299,
          "shopId": 1,
        }
      ],
      "orderNum": "abc2511483616883663",
      "orderStatusStr": "已完成",
      "orderTime": 1483616883000,
      "totalPrice": 0
    }
  ],
}

Page({
  data: {
    curNav: "0",
    list: ["全部","待付款","已完成"]
  },
  switchTab: function(e) {
  },
  onLoad: function() {
    this.setData({
      curNav: 0,
    });
  },
  onShow: function() {
    var self = this;
    var self  = self,
        info  = order_detail.data;
    info.forEach(function(value){
      value.timer = common.formatTime(new Date(value.orderTime), "yyyy-MM-dd hh:mm");
    });
    self.setData({
      order: info
    });
  },
  getDetail: function(e) {

  },
  goPay: function() {

  },
})