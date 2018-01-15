var app =getApp()
Page({
  data:{
    "text":"新人有礼",
    
  },
  onLoad:function(options){
    var num = "46512.65231";
    // 页面初始化 options为页面跳转所带来的参数
    console.log(options);
    this.setData({
      shu : num.substring(2,8),
      hh : 131.123123.toFixed(1)
    })
  }
})