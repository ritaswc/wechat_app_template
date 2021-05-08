var app    = getApp();
var common = require('../../util/util.js');
var detail = {
  data: [
    {
      title: "厨房用品",
      goodsList: [
        {
          goodsImg: "../../image/test4.jpg",
          goodsName: "商品1",
          oldPrice: "299",
          sellPrice: "5",
          tagline: "商品1 描述",
        },
        {
          goodsImg: "../../image/test3.jpg",
          goodsName: "商品2",
          oldPrice: "299",
          sellPrice: "5",
          tagline: "商品2 描述",
        },{
          goodsImg: "../../image/test1.jpg",
          goodsName: "商品3",
          oldPrice: "299",
          sellPrice: "5",
          tagline: "商品3 描述",
        }
      ]
    },
    {
      title: "特色活动",
      goodsList: [
        {
          goodsImg: "../../image/test5.jpg",
          goodsName: "商品4",
          oldPrice: "299",
          sellPrice: "5",
          tagline: "商品4 描述",
        },{
          goodsImg: "../../image/test2.jpg",
          goodsName: "商品5",
          oldPrice: "299",
          sellPrice: "5",
          tagline: "商品5 描述",
        },
      ]
    }
  ]
}
Page({
  data: {
    curNav: "0"
  },
  switchTab: function(e) {
    var self  = this,
        index = e.currentTarget.dataset.index,
        info = detail.data[index];
      self.setData({
        list: info,
        curNav: index,
      });
  },
  onLoad: function() {
    var self  = this,
        index = 0,
        info = detail.data;
    self.setData({
      product: info,
      list: info[index],
    });
  },
  onShow: function() {
  },
  getDetail: function(e) {
    var data       = e.currentTarget.dataset.value;
  }
})