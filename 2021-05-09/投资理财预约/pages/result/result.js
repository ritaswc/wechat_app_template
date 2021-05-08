// pages/result/result.js
var app = getApp();
Page({
  data:{
    windowHeight: 0,
    title: '预约成功',
    bodyShow: false,
    bgImg: '../../image/bgImg.jpg',
    imgSuccess: '../../image/imgSuccess.png',
    imgT9Logo: '../../image/iconLogo.png'
  },
  onLoad:function(options){
    wx.setNavigationBarTitle({
      title: this.data.title
    })
    this.setData({
      windowHeight: app.globalData.systemInfo.windowHeight
    });
    // 页面初始化 options为页面跳转所带来的参数
  },
  onReady:function(){
    // 页面渲染完成
    this.setData({
      bodyShow: true
    })
  },
  onShow:function(){
    // 页面显示
  },
  onHide:function(){
    wx.navigateBack();
    // 页面隐藏
  },
  onUnload:function(){
    
  }
  // 定义分享
  // onShareAppMessage: function () {
  //   return {
  //     title: '泰九智投产品预约',
  //     desc: '泰九智能投顾产品热销中，欢迎预约购买！',
  //     path: '/pages/index/index'
  //   }
  // }
})