//获取应用实例
var app = getApp()
Page({
  data: {
    curSelClassifyIndex: 1
  },
  onLoad() {
    console.log('swiper.js onLoad')
  }
  //事件处理函数
  ,onTitleBarsClick0(e) {
    console.log('listview.js onTitleBarsClick0');
    this.setData({
      curSelClassifyIndex: 0
    })
  },
  onTitleBarsClick1(e) {
    console.log('listview.js onTitleBarsClick1');
    this.setData({
      curSelClassifyIndex: 1
    })
  }
  ,onBindchange(e) {
    // this.checkInitLoadGankData()
    console.log(e.target);
    this.setData({
      curSelClassifyIndex: e.detail.current
    })
  }
})
