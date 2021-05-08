// pages/me/me.js
var app = getApp()

const config = require('../../config')
var util = require('../../utils/util.js')

var headImg, token, nickName, userId, userName
var alreadyGoToLogin = false;
Page({
  data: {
    motto: '世界上唯一不变的，就是一切都在变',
    userInfo: {},
  },
  //事件处理函数
  bindViewTap: function () {
    wx.navigateTo({
      url: '/pages/logs/logs'
    })
  },
  //点击登录 或 退出登录
  tapBtnLoginOrLoginOut: function (e) {
    let statusStr = e.currentTarget.dataset.loginStatus
    //'登录' : '退出登录'

    if (statusStr == "登录") {
      wx.navigateTo({
        url: '/pages/login/login',
      })
    } else if (statusStr == "退出登录") {
      //网络请求-退出登录
      this.requestLogout()
    }
  },
  //退出登录
  requestLogout: function () {
    var that = this;
    let url = config.logout

    var para = {
      "system": "02",
      "currentVersion": "3.1.81",
      "imei": "C75C7019-29FA-4F2B-8311-BAA6F29D1845",
      "model": "iPhone 5s (A1457\/A1518\/A1528\/A1530)",
      "systemVersion": "10.3.2",
      "accessToken": token,
      "sig": ""
    }

    wx.showLoading({ title: '加载中...' })

    util.RequestManager(url, para, function (res, fail) {

      wx.hideLoading()

      if (res.code == app.globalData.res_success) {
        //退出登录成功
        wx.clearStorage()
        that.setData({ userInfo: null })
      }
    })
  },

  onLoad: function (options) {
    // 页面初始化 options为页面跳转所带来的参数


    //微信授权登陆
    // //调用应用实例的方法获取全局数据
    // app.getUserInfo(function (userInfo) {
    //   //更新数据
    //   that.setData({
    //     userInfo: userInfo
    //   })
    // })
  },
  onReady: function () {
    // 页面渲染完成
  },
  onShow: function () {
    // 页面显示
    var that = this

    //获取好公仆登录信息
    //headImg token nickName userId userName
    headImg = wx.getStorageSync('headImg')    //同步获取指定key对应的内容
    token = wx.getStorageSync('token')
    nickName = wx.getStorageSync('nickName')
    userId = wx.getStorageSync('userId')
    userName = wx.getStorageSync('userName')

    var userDic = {
      "headImg": headImg,
      "token": token,
      "nickName": nickName,
      "userId": userId,
      "userName": userName
    }

    that.setData({ userInfo: userDic })

    if (alreadyGoToLogin == false) {

      //(!headImg || !token || !nickName || !userId || !userName)
      if (!headImg || !token || !userId || !userName) {
        //登录无效
        //1. - 清空用户缓存
        wx.clearStorage()
        //2. - 跳转到登录界面
        wx.navigateTo({
          url: '/pages/login/login',
        })

        //设置标志位
        alreadyGoToLogin = true;

        return;
      }
    }

  },
  onHide: function () {
    // 页面隐藏
  },
  onUnload: function () {
    // 页面关闭
  }
})