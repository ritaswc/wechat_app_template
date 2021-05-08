//index.js
//获取应用实例
var app = getApp()
Page({
  data: {
    status: 0,
    currentPosition: "order0",
    imgUrls: [
      "/images/1.jpg",
      "/images/1.jpg",
      "/images/1.jpg"
    ]
  },
  changeMenu: function (e) {
    var index = e.currentTarget.dataset.index;
    this.setData({
      status: index,
      currentPosition: "order" + index
    })
  },
  scrollBottom: function () {
    wx.showLoading({
      title: '加载中...',
      mask:true
    })
    setTimeout(function(){
      wx.hideLoading();
    },1000)
  },
  previewImages: function () {
    wx.previewImage({
      current: 'http://p1.meituan.net/460.280/deal/5cae86dd953bc50457aea6219e85287d79414.jpg@460w_280h_1e_1c',
      urls: [
        'http://p1.meituan.net/460.280/deal/5cae86dd953bc50457aea6219e85287d79414.jpg@460w_280h_1e_1c',
        'http://p1.meituan.net/460.280/deal/5cae86dd953bc50457aea6219e85287d79414.jpg@460w_280h_1e_1c',
        'http://p1.meituan.net/460.280/deal/5cae86dd953bc50457aea6219e85287d79414.jpg@460w_280h_1e_1c',
        'http://p1.meituan.net/460.280/deal/5cae86dd953bc50457aea6219e85287d79414.jpg@460w_280h_1e_1c'
      ],
    })
  }
})
