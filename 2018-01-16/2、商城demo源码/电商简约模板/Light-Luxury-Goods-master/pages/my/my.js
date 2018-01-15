// pages/my/my.js
var app = getApp();
Page({
  data:{
    userInfo: {},
    array: [{
      id: '待付款', address: 'http://o7qokh4e6.bkt.clouddn.com/%E5%BE%85%E4%BB%98%E6%AC%BE.png'
    },
    {
      id: '待发货', address: 'http://oalppxaqn.bkt.clouddn.com/%E5%BE%85%E5%8F%91%E8%B4%A7.png'
    },
    {
      id: '待收货', address: 'http://oalppxaqn.bkt.clouddn.com/%E5%BE%85%E6%94%B6%E8%B4%A7.png'
    }]
  },
  onLoad:function(options){
    // 页面初始化 options为页面跳转所带来的参数
    var that = this;
    app.getUserInfo(function(userInfo) {
      that.setData({
        userInfo: userInfo
      });
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
  },
  goAllOrder: function() {

  },
  goAddressManager: function() {
    
  }
})