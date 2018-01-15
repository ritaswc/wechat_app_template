// pages/userNav/myAccount/myAccount.js
var app=getApp();
Page({
  data:{
     
  },
  logoutAction:function(){
      app.logOut(function(res){
           console.log('退出成功')
           wx.removeStorageSync('userData');
           var pages=getCurrentPages();
           var currPage=pages[pages.length-1];
           var prevPage=pages[pages.length-2];
           prevPage.onLoad();
           wx.navigateBack({
             delta: 1, // 回退前 delta(默认为1) 页面
             success: function(res){
               // success
             }
           })
      });
  },
  onLoad:function(options){
    // 页面初始化 options为页面跳转所带来的参数
  }
})