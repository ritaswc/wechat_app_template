// pages/find/find.js
Page({
  data: {
    active: 0
  },
  onLoad: function (options) {
    // 页面初始化 options为页面跳转所带来的参数
  },
  hotClick() {
    console.log(1)
    this.setData({
      active: 0
    })
  },
  aroundClick() {
    this.setData({
      active: 1
    })
  },
  hongkongClick() {
    this.setData({
      active: 2
    })
  },
  aomenClick() {
    this.setData({
      active: 3
    })
  }
})