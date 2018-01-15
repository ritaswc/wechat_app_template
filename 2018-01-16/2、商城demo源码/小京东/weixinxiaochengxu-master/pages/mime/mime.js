//mime.js
var util = require('../../utils/util.js')
var request = require('../../utils/https.js')
var uri = 'memberapi/memberDetail'
var app = getApp()
var Info = {}
Page({
  data: {
    userInfo: {}
  },
  //事件处理函数
  bindViewTap: function () {

  },
  onLoad: function () {
  },
  no_payment: function () {
    //全部订单
    if (!Info.token) {
      //跳转到login
      wx.navigateTo({
        url: '../login/login?id=' + 0
      })
    }
    wx.navigateTo({
      url: '../ordertotal/ordertotal?id=' + 0

    })
  },
  already_shipped: function () {
    if (!Info.token) {
      //跳转到login
      wx.navigateTo({
        url: '../login/login?id=' + 1
      })
    }
    //待付款
    wx.navigateTo({
      url: '../ordertotal/ordertotal?id=' + 1
    })
  },
  no_comment: function () {
    if (!Info.token) {
      //跳转到login
      wx.navigateTo({
        url: '../login/login?id=' + 2
      })
    }
    //待收货
    wx.navigateTo({
      url: '../ordertotal/ordertotal?id=' + 2
    })
  },
  //售后
  customer_service: function () {
    if (!Info.token) {
      //跳转到login
      wx.navigateTo({   //加个参数  
        url: '../login/login?id=' + 3
      })
    }else{
        wx.navigateTo({   //加个参数  
        url: '../service/service'
      })
    }
  },
  coupon: function () {
    //优惠券
     wx.navigateTo({   //加个参数  
        url: '../coupon/coupon'
      })
  },
  //账户管理
  mimeinfo: function () {
    wx.navigateTo({
      url: '../manager/manager'
    })
  },
  onShow: function () {
    var that = this
    //判断是否登陆，如果没登陆走微信的
    var CuserInfo = wx.getStorageSync('CuserInfo');
    Info = CuserInfo
    if (CuserInfo.token) {
      //获取照片和用户名
      var photo = 'http://testbbcimage.leimingtech.com' + CuserInfo.avatar_url;
      var name = CuserInfo.loginname;
      var user = {}
      user.avatarUrl = photo;
      user.nickName = name;
      that.setData({ userInfo: user })
    } else {
      //调用应用实例的方法获取全局数据
      app.getUserInfo(function (userInfo) {
        //更新数据
        that.setData({
          userInfo: userInfo
        })
      })
    }
  }
})