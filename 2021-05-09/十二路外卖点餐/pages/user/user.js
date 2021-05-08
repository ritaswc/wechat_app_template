// pages/user/user.js
//获取应用实例
var app = getApp();
Page({
  data: {
    userInfo: {},
    timeCounter: null
  },
  onLoad: function (options) {
    // 页面初始化 options为页面跳转所带来的参数
    var that = this
    //调用应用实例的方法获取全局数据
    that.countDown(1800);
    that.setData({
      userInfo: app.globalData.userInfo
    })
  },
  formateTime: function (total) {
    var s = (total % 60) < 10 ? ('0' + total % 60) : total % 60;
    var h = total / 3600 < 10 ? ('0' + parseInt(total / 3600)) : parseInt(total / 3600);
    var m = (total - h * 3600) / 60 < 10 ? ('0' + parseInt((total - h * 3600) / 60)) : parseInt((total - h * 3600) / 60);
    return h + ' : ' + m + ' : ' + s;
  },
  countDown: function (total) {
    var that = this;
    that.setData({
      timeCounter: that.formateTime(total)
    });
    if (total <= 0) {
      that.setData({
        timeCounter: "已经截止"
      });
      return;
    }
    setTimeout(function () {
      total--;
      that.countDown(total);
    }, 1000)
  },
  payBtn:function(){
    wx.navigateTo({
      url: '/pages/nopay/nopay'
    })
  }
})