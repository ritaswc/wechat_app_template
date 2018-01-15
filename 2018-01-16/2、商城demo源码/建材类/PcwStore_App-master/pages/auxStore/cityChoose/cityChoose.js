// pages/auxStore/cityChoose/cityChoose.js
var app=getApp();

Page({
  data:{
     local:'上海',
     clocal:'1',
     city:[],
     currIndex:'1'
  },
  localEvent:function(e){
     var local=e.currentTarget.dataset.local;
     var index=e.currentTarget.dataset.cindex;
     var pages=getCurrentPages();
     var curPage=pages[pages.length-1];
     var prePage=pages[pages.length-2];
     prePage.setData({
        theCity:index,
        cityName:local
     })
     prePage.onLoad();
     wx.navigateBack();
  },
  cityEvent:function(e){
     var index = parseInt(e.currentTarget.dataset.index);
     var name=e.currentTarget.dataset.cityname;
     console.log(index,name);
     var pages=getCurrentPages();
     var curPage=pages[pages.length-1];
     var prePage=pages[pages.length-2];
     prePage.setData({
        theCity:index,
        cityName:name
     })
     prePage.onLoad();
     wx.navigateBack();
  },
  onLoad:function(options){
    // 页面初始化 options为页面跳转所带来的参数
    var that=this;
    app.getLocalCity(function(success){
        that.setData({
           local:success.data.data.goods_area,
           clocal:success.data.data.goods_area_id
       })
    })
    app.getAllCity(function(success){
       that.setData({
           city:success.data.data
       })
    })
  }
})