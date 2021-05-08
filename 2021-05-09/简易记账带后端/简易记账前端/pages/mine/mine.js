//index.js
//获取应用实例
var app = getApp()
Page({
  data: {
    userInfo: {},
  
  },
  //事件处理函数
  bindViewTap: function() {
   
  },
  onLoad: function () {
  this.setData({
    userInfo:app.globalData.userInfo
  });
  }
})