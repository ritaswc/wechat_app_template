// pages/toLogin/toLogin.js
var app=getApp();
Page({
  data:{
    userTel:'',
    userPassw:''
  },
  bindUserTel:function(e){
     this.setData({
       userTel:e.detail.value
     })
     wx.setStorageSync('userTel', this.data.userTel);   
  },
  bindUserPassw:function(e){
     this.setData({
       userPassw:e.detail.value
     })
     wx.setStorageSync('userPassw', this.data.userPassw);
  },
  loginAction:function(){
      var that=this;
      var userTel=that.data.userTel;
      var userPassw=that.data.userPassw;
      // console.log(userTel,userPassw);
      app.login(userTel,userPassw,function(success){
            // console.log(success);
            if(success.data.success=="true"){
               wx.setStorageSync('userData', success.data.data);
               var pages = getCurrentPages();
               var currPage = pages[pages.length - 1];   //当前页面
               var prevPage = pages[pages.length - 2];  //上一个页面
               prevPage.onLoad();
               wx.navigateBack({
                 delta: 1, // 回退前 delta(默认为1) 页面
                 success: function(res){
                   // success
                 }
               })
              //  var x= wx.getStorageSync('userData');
              //  wx.navigateBack({
              //    delta: 1, // 回退前 delta(默认为1) 页面
              //    success: function(res){
              //       wx.setStorageSync('userData', success.data.data);
              //    }
              //  })
            }else{
              wx.showToast({
                 title:success.data.error.msg,
                 icon:'loading',
                 duration:1000
              })
              // console.log(success.data.error.msg)
            }
      })
  },
  onLoad:function(options){
    console.log('loginLauching......')
    // 页面初始化 options为页面跳转所带来的参数
    var that=this;
    that.setData({
      userTel:wx.getStorageSync('userTel') || []
    })
    //  that.setData({
    //   userPassw:wx.getStorageSync('userPassw') || []
    // })
  }
})