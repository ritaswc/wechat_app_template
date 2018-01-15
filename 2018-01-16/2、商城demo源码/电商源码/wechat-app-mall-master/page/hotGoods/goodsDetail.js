var app    = getApp();
var common = require('../../util/util.js');
var detail = {
  "data": {
    "details": ["../../image/product.jpg","../../image/product.jpg","../../image/product.jpg"],
    "endTime": 1485070860000,
    "imgBanner": "../../image/product.jpg",
    "oldPrice": "5666",
    "sellPrice": "2",
    "standard": '[{"0":"m","1":"l","name":"尺码","code":"cm"},{"1":"蓝色","0":"红色","name":"颜色","code":"ys"}]',
    "startTime": 1488866679350,
    "storeTotal": 5,
    "tagline": "123",
    "title": "添加测试",
  }
}
Page({
  data: {
    indicatorDots: true,
    autoplay: true,
    interval: 5000,
    duration: 1000,
    circular: true,
    isShow: false,
    warning: false,
    warnDes: "",
    number: 1,
    postData: {},
  },
  postData: {},
  onLoad: function(e) {
    var self      = this,
        item      = detail.data;
    item.property = common.goodsPropFilter(item.standard);
    self.setData({
      storeTotal: item.storeTotal,
      item: item,
      imgUrls: common.strToArray(item.imgBanner),
    })
  },
  onShow: function(e) {
  },
  onHide: function() {
  },
  closeBanner: function() {
    this.setData({
      isShow: false,
    })
  },
  checkGoods: function() {
    this.setData({
      isShow: true,
    })
  },
  getChecked: function(e) {
    var self            = this,
        haveCheckedProp = "",
        name            = e.currentTarget.dataset.property,
        value           = e.currentTarget.dataset.value,
        length, objLength;
    self.postData[name] = value;
    length              = self.data.item.property.length;
    objLength = common.objLength(self.postData);
    for(var key in self.postData) {
      haveCheckedProp += " " + self.postData[key];
    }
    if(length == objLength) {
      self.setData({
        getCount: true,
      });
    }
    this.setData({
      postData: self.postData,
      haveCheckedProp: haveCheckedProp
    })
  },
  goToCounter: function() {
    var self      = this,
        length    = self.data.item.property.length,   //属性num
        objLength = common.objLength(self.data.postData);   //已选择属性num
    if(self.data.item.storeTotal == 0) {
      common.alert.call(self, "库存不足");
    } else {
      if(length === objLength) {
        var number  = self.data.number,
            title   = self.data.item.title,
            tagline = self.data.item.tagline,
            price   = self.data.item.sellPrice,
            image   = self.data.imgUrls[0];
        wx.navigateTo({
          url: "counter?number=" + number + "&title=" + title + "&tagline=" + tagline + "&price=" + price + "&image=" + image,
          success: function(res) {
          }
        })
      } else {
        common.alert.call(self, "请选择商品属性");
      }
    }

  },
  addNum: function() {
    var self = this,
        num  = self.data.number;
    if(num + 1 > self.data.item.storeTotal) {
      common.alert.call(self, "超过最大库存");
    } else {
      num += 1;
      self.setData({
        number: num,
      })
    }
  },
  minusNum: function() {
    var self = this,
        num  = self.data.number;
    if(num - 1 < 1) {
      common.alert.call(self, "购买数量最少为1");
    } else {
      num -= 1;
      self.setData({
        number: num,
      })
    }
  }
})