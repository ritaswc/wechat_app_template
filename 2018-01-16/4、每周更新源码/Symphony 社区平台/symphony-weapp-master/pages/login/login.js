var calcMD5 = require('../../js/md5.js');
var Util = require('../util/util.js');
Page({
  data: {
  },
  /**
   * 初始化加载
   * options url 参数
   */
  onLoad: function (options) {
    wx.getStorage({
      key: 'cookie',
      success: function (res) {
        if (!res.data) {
          return false
        }
        wx.redirectTo({
          url: '../scan/scan'
        })
      }
    })
  },
  /**
   * 注册
   */
  register: function () {
    wx.showModal({
      content: '小程序暂不支持注册, 请前往 https://hacpai.com 进行注册',
      showCancel: false
    })
  },
  /**
   * 登录
   */
  login: function (e) {
    Util.networkStatus()
    wx.request({
      url: 'https://hacpai.com/login',
      data: {
        nameOrEmail: e.detail.value.userName,
        userPassword: calcMD5(e.detail.value.password),
        rememberLogin: true,
      },
      method: 'POST',
      header: {
        'content-type': 'application/json'
      },
      success: function (res) {
        if (res.errMsg !== 'request:ok') {
          wx.showToast({
            title: res.errMsg,
            icon: 'loading',
            duration: 3000
          })
          return false;
        }
        if (!res.data.sc) {
          wx.showToast({
            title: res.data.msg,
            icon: 'loading',
            duration: 3000
          })
          return false;
        }

        wx.setStorage({
          key: "cookie",
          data: res.data.token
        })

        wx.redirectTo({
          url: '../scan/scan'
        })
      },
      fail: function (res) {
        wx.showToast({
          title: res.errMsg,
          icon: 'loading',
          duration: 3000
        })
      }
    })
  }
})
