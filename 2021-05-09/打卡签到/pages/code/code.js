// pages/code/code.js

var Api = require('../../utils/api.js')

Page({
  data:{},
  onLoad:function(options){
    // 页面初始化 options为页面跳转所带来的参数

    this.setData({
      encrypt: options.encrypt
    })

    wx.getStorage({
      key: 'token',
      success: (res) => {
        // success
        this.setData({
          token: res.data
        })
      },
      fail: function() {
        wx.redirectTo({ url: '/pages/login/login' })
      }
    })


  },
  onReady:function(){
    // 页面渲染完成
    this.getLocation()
  },
  onShow:function(){
    // 页面显示
  },
  onHide:function(){
    // 页面隐藏
  },
  onUnload:function(){
    // 页面关闭
  },

  getLocation: function(cb) {
    wx.getLocation({
      type: 'wgs84', // 默认为 wgs84 返回 gps 坐标，gcj02 返回可用于 wx.openLocation 的坐标
      success: (res) => {
        // success
        this.punch(res.latitude, res.longitude)
        wx.showToast({
          title: '正在打卡',
          icon: 'loading',
          duration: 2000
        })
      },
      fail: function() {
        wx.redirectTo({
          url: '/pages/fail/fail',
        })
      }
    })
  },

  punch: function (latitude, longitude) {
    var now = new Date()
    wx.request({
      url: Api.punch + 'encrypt=' + this.data.encrypt + '&token=' + this.data.token,
      data: {
        latitude: latitude,
        longitude: longitude,
        time: now
      },
      method: 'POST',
      success: (res) => {
        // success

        if (res.statusCode == 201) {
          wx.navigateTo({
            url: '/pages/success/success?place=' + res.data.place + '&time=' + res.data.h_m_s + '&status=' + res.data.owner.status,
          })
        }
        else if (res.statusCode == 403) {
          wx.navigateTo({
            url: '/pages/fail/fail?info=' + '超出范围',
          })
        }
        else {
          wx.navigateTo({
            url: '/pages/fail/fail',
          })
        }
      }
    })
  },

})