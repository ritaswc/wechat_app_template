//获取应用实例
var app = getApp()
Page({
  data: {
    motto: 'Hello World',
    userInfo: {},
    curSelClassifyIndex: 0
  },

  onLoad() {
    console.log('listview onLoad')
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
  // ,onTitleBarsClick0(e) {
  //   this.setData({
  //     curSelClassifyIndex: 0
  //   })
  //
  // },
  // onTitleBarsClick1(e) {
  //   this.setData({
  //     curSelClassifyIndex: 1
  //   })
  // },
  // ,onReady: function(){
  //   console.log('onReady');
  // },
  // onShow: function(){
  //   console.log('onShow');
  // },
  // onHide: function(){
  //   console.log('onHide');
  // },
  // onUnload(){
  //   console.log('onUnload');
  // }
})
