// pages/scan/scan.js

//按钮打卡页面

var Api = require('../../utils/api.js')
var util = require('../../utils/util.js')
var seconds = 10
var ing //定时器
Page({
  data: {
    encrypt: '',
    wxName: '',
    avatar: '',
    btnStr: '打卡',
    touchBled: false,
  },

   onShareAppMessage: function () {
     return {
       title: '打卡咯',
       path: '/pages/login/login?'
     }
   },


  onLoad: function (options) {

    this.setData({
      token: wx.getStorageSync('token')
    })

    this.getUserInfo((info) => {
      this.setData({
        encrypt: info.belongsTo._id,
        wxName: info.wxName,
        avatar: info.img
      })
    })
  },
  onReady: function () {
    // 页面渲染完成
    // this.getLocation()
    // this.getUserInfo()
  },
  onShow: function () {
    // 页面显示
    this.startTime()
  },
  onHide: function () {
    // 页面隐藏
  },
  onUnload: function () {
    // 页面关闭
  },

  //获取公司id和个人信息
  getUserInfo: function (cb) {
    wx.request({
      url: Api.userInfo + this.data.token,
      data: {},
      method: 'GET',
      success: function (res) {
        // success
        console.log(res)
        typeof cb == 'function' && cb(res.data)
      }
    })
  },

  //打卡按钮

  toClock: function () {
    wx.showNavigationBarLoading()
    ing = setInterval(() => { this.sleepOneMinute() }, 1000);
    console.log("2")

    wx.getLocation({
      type: 'wgs84',
      success: (res) => {
        console.log('location', res)
        this.setData({
          touchBled: true
        })
        this.punch(res.latitude, res.longitude)
      },
      fail: (res) => {
        wx.hideNavigationBarLoading()
        this.setData({
          info: '打卡功能需要获取您的地理位置信息，请稍后重试'
        })
      }
    })
  },

  punch: function (latitude, longitude) {
    var now = new Date()
    console.log(now)
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
        wx.hideNavigationBarLoading()
        console.log(res)
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
            url: '/pages/fail/fail=',
          })
        }
      }
    })
  },

  toList: function () {
    wx.navigateTo({
      url: '/pages/self/self',
    })
  },

  startTime: function () {
    var today = new Date();
    var month = today.getMonth();
    var day = today.getDate();
    var week = today.getDay();
    var h = today.getHours();
    var m = today.getMinutes();
    var s = today.getSeconds();// 在小于10的数字钱前加一个‘0’
    month = this.checkTime(month);
    day = this.checkTime(day);
    m = this.checkTime(m);
    s = this.checkTime(s);
    this.setData({
      hours: h,
      minutes: m,
      seconds: s,
      month: util.translateMonth(month),
      day: day,
      week: util.translateWeek(week)
    })

    var t = setTimeout(() => { this.startTime() }, 500);
  },

  checkTime: function (i) {
    if (i < 10) {
      i = "0" + i;
    }
    return i;
  },

  sleepOneMinute: function () {
    if (this.data.touchBled == true) {
      seconds--
      this.setData({
        btnStr: (seconds + ' 秒后可再打卡')
      })
      if (seconds == 0) {
        this.setData({
          btnStr: '打卡',
          touchBled: false,
        })
        seconds = 10
        clearInterval(ing)
      }
    }
  }
})