// pages/destination/cityView/cityView.js
Page({
  data:{},
  onLoad:function(options){
    // 页面初始化 options为页面跳转所带来的参数
    let cityname=options.cityname;
    wx.setNavigationBarTitle({
      title: ''+cityname+''
    })
  }
})