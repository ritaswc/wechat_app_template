// pages/login/login.js

const requestUrl = require('../../config').requestUrl

var login = function (that) {
  wx.request({
    url: requestUrl + 'wxLogin.ashx',

    data: {
      userName: that.data.name,
      userPwd: that.data.pwd
    },

    success: function (res) {
      if (res.data.ChinaValue[0].Result == 'True') {
        //登录状态写入缓存
        wx.setStorage({
          key: "IsLogin",
          data: true
        })
        wx.switchTab({
          url: '../index/index'
        })
      }
      else {
        wx.showToast({
          title: '口令错误',
          image: "../../images/icon-no.png",
          mask: true,
          duration: 1000
        })
      }
    }
  })
}

Page({
  data: {
    name: '',
    pwd: ''
  },

  bindNameInput: function (e) {
    this.setData({
      name: e.detail.value
    })
  },

  bindPwdInput: function (e) {
    this.setData({
      pwd: e.detail.value
    })
  },

  bindInputLogin: function (e) {
    login(this)
  },

  formLogin: function (e) {
    login(this)
  },

  onLoad: function (e) {
    wx.getStorage({
      key: 'IsLogin',
      success: function (res) {
        if (res.data) {
          wx.switchTab({
            url: '../index/index'
          })
        }
      }
    })
  }
})