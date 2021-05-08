// pages/index/inventory/inventory.js
var util = require('../../../../../utils/util.js')

//获取二级信息
var getSeconds = function (that) {
  util.requestSupply("getStyleStores", "?styleId=" + that.data.styleId,
    function (res) {
      console.log(res.mapResults);
      that.setData({
        secondId: res.mapResults.secondId,
        detailId: res.mapResults.detailId,
        seconds: res.mapResults.seconds,
        colors: res.mapResults.colors,
        stores: res.mapResults.stores
      });
    }, function (res) {
      console.log(res);
    });
}

//获取对应的颜色信息
var getColors = function (that) {
  var stores = that.data.stores
  util.requestSupply("getStyleColorAndStore", "?styleId=" + that.data.styleId + '&secondId=' + that.data.secondId,
    function (res) {
      that.setData({
        secondId: res.mapResults.secondId,
        detailId: res.mapResults.detailId,
        colors: res.mapResults.colors,
        stores: res.mapResults.stores
      });
    }, function (res) {
      console.log(res);
    });
}

//获取对应的库存信息
var getStores = function (that) {
  util.requestSupply("getWeixinStyleStore", "?detailId=" + that.data.detailId,
    function (res) {
      var results = res.results;
      console.log(results);
      that.setData({
        stores: results
      });
    }, function (res) {
      console.log(res);
    });
}

Page({
  data: {
    weixinCtx: util.weixinCtx,
    seconds: [],
    colors: [],
    stores: [],
    secondId: 0,
    detailId: 0
  },
  onLoad: function (option) {
    var that = this
    that.setData({
      styleId: option.styleId
    })
    getSeconds(that);
  },
  searchColors: function (e) {
    var that = this
    that.setData({
      secondId: e.currentTarget.dataset.secondid
    });
    getColors(that);
  },
  searchStores: function (e) {
    var that = this
    that.setData({
      detailId: e.currentTarget.dataset.detailid
    });
    getStores(that);
  }
})
