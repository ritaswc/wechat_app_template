var app    = getApp();
var common = require('../../util/util.js');
var detail = {
  data: [
    {
      title: "厨房用品",
      imageList: ["../../image/test.jpg","../../image/test1.jpg",
        "../../image/test2.jpg","../../image/test3.jpg"
      ],
      goodsList: [
        {
          goodsImg: "../../image/test.jpg",
          goodsName: "商品1",
          oldPrice: "299",
          sellPrice: "5",
          tagline: "商品1 描述",
        },
        {
          goodsImg: "../../image/test1.jpg",
          goodsName: "商品2",
          oldPrice: "299",
          sellPrice: "5",
          tagline: "商品2 描述",
        },{
          goodsImg: "../../image/test2.jpg",
          goodsName: "商品3",
          oldPrice: "299",
          sellPrice: "5",
          tagline: "商品3 描述",
        }
      ]
    },
    {
      title: "特色活动",
      imageList: ["../../image/test4.jpg","../../image/test5.jpg",
        "../../image/test1.jpg"
      ],
      goodsList: [
        {
          goodsImg: "../../image/test3.jpg",
          goodsName: "商品4",
          oldPrice: "299",
          sellPrice: "5",
          tagline: "商品4 描述",
        },{
          goodsImg: "../../image/test.jpg",
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
    indicatorDots: true,
    autoplay: true,
    interval: 5000,
    duration: 1000,
    curNav: "0",
    circular: true,
  },
  onLoad: function() {
  },
  onHide: function() {
  },
  onShow: function() {
    var self   = this,
        info   = detail.data,
        info_des = info[0];
    self.setData({
      product: info,
      list: info_des,
      length: info.length
    });
  },
  switchTab: function(e) {
    var self  = this,
        index = e.currentTarget.dataset.index;
    var info  = detail.data[index];
    info.goodsList.forEach(function(value) {
      value.timer = common.formatTime(new Date(value.beginTime), "yyyy-MM-dd hh:mm");
      self.setData({
        list: info,
        curNav: index
      });
    });
  },
  getDetail: function(e) {
    var data       = e.currentTarget.dataset.value,
        goodsId = data.id;
    wx.navigateTo({
      url: "goodsDetail?goodsId=" + goodsId,
      success: function(res) {
      },
    })
  },
})