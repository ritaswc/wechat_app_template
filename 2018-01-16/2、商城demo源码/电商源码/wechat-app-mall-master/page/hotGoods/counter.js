var app    = getApp();
var common = require('../../util/util.js');
Page({
  data: {
    actGoodsId: ""
  },
  goPay: function() {
    wx.switchTab({
      url: "../personal/personal",
      success: function(res) {
        wx.navigateTo({
          url: "../order/order",
          success: function(res) {
          }
        })
      }
    })
  },
  goToAddr: function() {
    wx.navigateTo({
      url: "../personal/editAddr",
      success: function(res) {
      },
    })
  },
  onLoad: function(e) {
    var number = e.number,
        tagline = e.tagline,
        title = e.title,
        price = e.price,
        image = e.image;
    var self = this;
    self.setData({
      number: number,
      tagline: tagline,
      title: title,
      price: price,
      image: image,
      actGoodsId: e.actGoodsId,
      goodsId: e.goodsId,
      standardCode: e.standardCode
    });
  },
  editAddr: function(e) {
    var id = e.currentTarget.dataset.id;
    wx.navigateTo({
      url: "../personal/address",
      success: function(res) {
      },
    })
  },
  onShow: function() {

  }
})