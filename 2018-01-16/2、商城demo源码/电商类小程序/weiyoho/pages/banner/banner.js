// pages/banner/banner.js
var login = require('../../utils/uctoo-login.js');
var app = getApp();
Page({
  data:{},
  onLoad:function(options){
    // 页面初始化 options为页面跳转所带来的参数
     login.login();
  },
    //分享
  onShareAppMessage: function () {
    return {
      title: '微友货',
      path: '/pages/banner/banner'
    }
  },
  banner:function(){
    wx.switchTab({
      url: '/pages/index/index',
      success: function(res){
        // success
      },
      fail: function() {
        // fail
      },
      complete: function() {
        // complete
      }
    })
  },
  onReady:function(){
    // 页面渲染完成
  },
  onShow:function(){
    // 页面显示
  },
  onHide:function(){
    // 页面隐藏
  },
  onUnload:function(){
    // 页面关闭
  }
})