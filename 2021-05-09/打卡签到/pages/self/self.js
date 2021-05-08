// pages/self/self.js

var Api = require('../../utils/api.js')
var util = require('../../utils/util.js')

Page({
  data: {
    date: '2017-03-02',
    lists: []
  },
  onLoad: function (options) {
    // 页面初始化 options为页面跳转所带来的参数
    this.setData({
      token: wx.getStorageSync('token')
    })
    wx.getSystemInfo({
      success: (res) => {
        this.setData({
          winWidth: res.windowWidth,
          winHeight: res.windowHeight
        })
      }
    })
  },
  onReady: function () {
    // 页面渲染完成
  },
  onShow: function () {
    // 页面显示
    util.obtainIndate((Inday) => {
      this.setData({
        date: Inday
      })
      this.getSelfClockList()
    })

  },
  onHide: function () {
    // 页面隐藏
  },
  onUnload: function () {
    // 页面关闭
  },

  getSelfClockList: function () {

    wx.request({
      url: Api.selftList + this.data.token + '&today=' + this.data.date,
      data: {
        // today: this.data.date
      },
      method: 'GET',
      success: (res) => {
        // success
        console.log(res)
        if (res.data.length == 0) {
          this.setData({
            lists: []
          })
        }
        else {
          this.setData({
            lists: res.data[0].sweeps
          })
        }
      },
      fail: function () {
        // fail
      },
      complete: function () {
        // complete
      }
    })
  },

  handleQuit: function () {
    wx.showModal({
      title: '警告',
      content: '退出之后将删除所有个人信息，您确定么？',
      success: (res) => {
        if (res.confirm) {
          console.log('用户点击确定')
          this.quitCompany()
        }
      }
    })
  },

  quitCompany: function () {
    wx.request({
      url: Api.tofree + this.data.token,
      data: {},
      method: 'DELETE',
      success: (res) => {
        // success
        if (res.statusCode == 200) {
          wx.setStorageSync('userType', 'user')
          wx.redirectTo({
            url: '/pages/select/select'
          })
        }
        else {
          wx.navigateTo({
            url: '/pages/login/login',
          })
        }
      }
    })
  },

  //日期监听
  bindDateChange: function (e) {
    this.setData({
      date: e.detail.value
    })
    this.getSelfClockList()
  },
})