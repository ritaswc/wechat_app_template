var app = getApp()
const profile = require('../../utils/profile.js')


Page({
  data: {
    needBindMobile: true,
    useCodeToLogIn: true,
    mobile: '',
    userInfo: {
      avatarUrl: '',
      nickName: ''
    },
    zichan_slides: [],
    baye_rank: null,
    disableGetMobileCode: false,
    disableSubmitMobileCode: true,
    getCodeButtonText: '获取验证码'
  },

  onShow: function() {
  },

  onLoad: function() {
    var that = this
    that.setData({userInfo: app.globalData.userInfo})

    if (app.globalData.token) {
      profile.postCustomerInfo({}, that.infoCallback)
    } else {
      var token = wx.getStorageSync('userToken')
      if (token) {
        app.globalData.token = token
        profile.postCustomerInfo({}, that.infoCallback)
      }
    }
 
  },

  bindGetPassCode: function(e) {
    var that = this
    that.setData({disableGetMobileCode: true})
    profile.getPassCode(this.data.mobile, function(res) {
      if (res.data.code === 20001) {
        wx.showToast({
          title: `${res.data.message}`,
          icon: 'success',
          duration: 2000
        })
        that.countDownPassCode()
      } else {
        that.setData({disableGetMobileCode: false})
        wx.showToast({
          title: `${res.data.message}`,
          icon: 'fail',
          duration: 2000
        })
      }
    })
  },

  countDownPassCode: function() {
    var pages = getCurrentPages()
    var i = 60
    var intervalId = setInterval(function(){
      i--
      if (i<=0) {
        pages[pages.length-1].setData({
          disableGetMobileCode: false,
          disableSubmitMobileCode: false,
          getCodeButtonText: '获取验证码'
        })
        clearInterval(intervalId)
      } else {
        pages[pages.length-1].setData({
          getCodeButtonText: i,
          disableGetMobileCode: true,
          disableSubmitMobileCode: false
        })
      }
    },1000);
  },

  bindCheckMobile: function(mobile) {
    if (!mobile.match(/^1[3-9][0-9]\d{8}$/)) {
      wx.showModal({
        title: '错误',
        content: '手机号格式不正确，仅支持国内手机号码'
      })
      return false
    }
    return true
  },

  bindInputMobile: function(e) {
    this.setData({
      mobile: e.detail.value,
    })
  },

  bindLoginMobilecode: function(e) {
    if (!this.bindCheckMobile(this.data.mobile)) { return }
    if (!(e.detail.value.code && e.detail.value.code.length === 4)) { return }
    wx.showToast({
      title: '登录中...',
      icon: 'loading',
      duration: 5000
    })
    var data = {mobile: this.data.mobile, mobile_code: e.detail.value.code, name: this.data.userInfo.nickName}
    profile.postCustomerInfo(data, this.infoCallback)
  },

  bindLoginPassword: function(e) {
    var mobile = e.detail.value.mobile
    if (!this.bindCheckMobile(mobile)) { return }
    if (!e.detail.value.password) {
      wx.showModal({
        title: '错误',
        content: '请输入密码'
      })
      return
    }

    wx.showToast({
      title: '登录中...',
      icon: 'loading',
      duration: 5000
    })
    var data = {mobile: mobile, password: e.detail.value.password}
    profile.postCustomerInfo(data, this.infoCallback)
  },

  changeLoginType: function(e) {
    this.setData({useCodeToLogIn: !this.data.useCodeToLogIn})
  },

  bindLogout: function(e) {
    var that = this
    app.request({
      url: `${app.globalData.API_URL}/sessions/logout`,
      data: {code: app.globalData.code},
      method: 'POST',
      success: function(res) {
        if (parseInt(res.statusCode) === 200) {
          wx.removeStorage({ key: 'token' })
          app.globalData.currentCustomer = null
          app.globalData.token = null
          app.globalData.code = null
          app.globalData.userInfo = null
          app.getUserInfo(function(userInfo) {
            that.setData({userInfo: userInfo})
          })
          that.setData({
            needBindMobile: true,
            baye_rank: ''
          })
        }
      },
      fail: function() {},
      complete: function() {}
    })
  },

  infoCallback: function(currentCustomer) {
    var that = this
    var baye_rank = currentCustomer.baye_rank
    that.setData({baye_rank: baye_rank})

    profile.getZichanSlides(function(result) {
      var data = getApp().store.sync(result.data)
      that.setData({'zichan_slides': data})
      wx.setStorage({
        key:"zichan_slides",
        data:data
      })
      wx.hideToast()
    })
  }
})
