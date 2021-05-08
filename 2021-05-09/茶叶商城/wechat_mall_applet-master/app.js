const jsonApi = require('utils/jsonapi-datastore/dist/jsonapi-datastore.js')
require('utils/polyfill.js')

App({
  onLaunch: function () {
    var that = this
    this.store = new(jsonApi.JsonApiDataStore)
    this.jsonModel = jsonApi.JsonApiDataStoreModel
    this.globalData.code = wx.getStorageSync('code')

    this.getUserInfo(function() {
      that.postEncryptedData(function(res){
        that.globalData.wechatUserType = res.data.wechat_user_type
      })
    })
    this.request({
      url: `${that.globalData.API_URL}/manage_features`,
      success: function(res) { that.globalData.featureManager = res.data }
    })
  },

  getUserInfo: function (cb) {
    var that = this
    if(this.globalData.userInfo){
      typeof cb == "function" && cb(this.globalData.userInfo)
    }else{
      wx.login({
        success: function (res) {
          if (res.code) {
            that.globalData.code = res.code
            wx.setStorageSync('code', res.code)
            wx.getUserInfo({
              success: function (res) {
                that.globalData.encrypted = {encryptedData: res.encryptedData, iv: res.iv}
                that.globalData.userInfo = res.userInfo
                typeof cb == "function" && cb(that.globalData.userInfo)
              }
            })
          } else {
            console.log('获取用户登录态失败！' + res.errMsg)
          }
        }
      })
    }
  },

  request: function(obj) {
    var header = obj.header || {}
    if (!header['Content-Type']) {
      header['Content-Type'] = 'application/json'
    }
    if (!header['Authorization']) {
      header['Authorization'] = this.globalData.token
    }

    // This must be wx.request !
    wx.request({
      url: obj.url,
      data: obj.data || {},
      method: obj.method || 'GET',
      header: header,
      success: function(res) {
        typeof obj.success == "function" && obj.success(res)
      },
      fail: obj.fail || function() {},
      complete: obj.complete || function() {}
    })
  },

  authRequest: function(obj) {
    var that = this
    if (!that.globalData.token) {
      var token = wx.getStorageSync('userToken')
      if (!token) {
        wx.hideToast()
        wx.showModal({
          title: '未登录',
          content: '请前往 “我的” 页面绑定手机号',
          showCancel: false,
          success: function(res) {
            // 清除没用的token
            wx.removeStorage({key: 'userToken'})
            that.globalData.token = undefined
            if (getCurrentPages().length > 1) {
              wx.navigateBack()
            }
          }
        })
        return
      }
      that.globalData.token = token
      that.request({
        url: `${that.globalData.API_URL}/sessions/login`,
        method: 'POST',
        data: {code: that.globalData.code},
        success: function(res) {
          if (!res.data.token) {
            wx.hideToast()
            wx.showModal({
              title: '未登录',
              content: '请前往 “我的” 页面绑定手机号',
              showCancel: false,
              success: function(res) {
                // 清除没用的token
                wx.removeStorage({key: 'userToken'})
                that.globalData.token = undefined
                if (getCurrentPages().length > 1) {
                  wx.navigateBack()
                }
              }
            })
          } else {
            that.globalData.currentCustomer = res.data.customer
            that.globalData.token = res.data.token
            wx.setStorage({
              key: 'userToken',
              data: res.data.token
            })
            that.request(obj)
          }
        },
        fail: function(res) {}
      })
    } else {
      that.request(obj)
    }
  },

  postEncryptedData: function (resolve) {
    this.request({
      method: 'POST',
      url: `${this.globalData.API_URL}/sessions/wechat_user_type`,
      data: {
        code: this.globalData.code,
        encrypted: this.globalData.encrypted,
        userInfo: this.globalData.userInfo
      },
      success: resolve,
      fail: function(res) {}
    })
  },

  globalData:{
    wechatUserType: 'normal',
    featureManager: {},
    userInfo: null,
    currentCustomer: null,
    // API_URL: 'http://localhost:3000',
    API_URL: 'https://rapi-staging.bayekeji.com'
  }
})
