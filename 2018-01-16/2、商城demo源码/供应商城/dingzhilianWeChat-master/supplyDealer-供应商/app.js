//app.js
App({
  onLaunch: function () {
    //调用API从本地缓存中获取经销商session判断是否过期,自动登录
    var that = this;
    var adminObj = wx.getStorageSync('dealerAdminObj');
    var password = wx.getStorageSync('dingzhilian_pwd');
    wx.checkSession({                                   
      success: function () {
        if (adminObj && password) {
          that.globalData.adminObj = adminObj;
          that.globalData.password = password;
          wx.request({
            url: that.globalData.requestUrl + 'weixinMerchant/checkSession',
            data: {
              phone: adminObj.phone,
              password: password,
              sessionId: adminObj.sessionId
            },
            success: function (res) {
              if (res.data == '0') {
                wx.navigateTo({
                  url: '/pages/index/index'
                })
                //wx.redirectTo({
                //  url: '/pages/index/index'
                //})
              }
            }
          })
        }
      },
      fail: function () {
        //微信登录状态过期
        wx.login()
      }
    })
  },
  getUserInfo: function (cb) {
    var that = this
    if (that.globalData.userInfo) {
      typeof cb == "function" && cb(that.globalData.userInfo)
    } else {
      //调用登录接口
      wx.login({
        success: function () {
          wx.getUserInfo({
            success: function (res) {
              that.globalData.userInfo = res.userInfo
              typeof cb == "function" && cb(that.globalData.userInfo)
              console.log(res.userInfo)
            }
          })
        }
      })
    }
  },
  globalData: {
    userInfo: null,
    //requestUrl: 'https://www.dingzhilian.com/beta/',
    requestUrl: 'http://localhost:8080/',
    //定制链经销商小程序图片url
    imageCtx: 'https://www.dingzhilian.com/upload_dz/',
    //缓存信息
    adminObj: '',
    password: '',
  },
  //警告弹框
  warning: function (content) {
    wx.showModal({
      content: content,
      confirmText: '关闭',
      showCancel: false
    })
  },
  //登录失效
  noLogin: function (content) {
    wx.showModal({
      content: content,
      confirmText: '关闭',
      showCancel: false,
      success: function (res) {
        if (res.confirm) {
          //所有页面退回首页
          wx.navigateBack({
            delta: 5
            //success:function(){
            //关闭首页去到登录页面
            //wx.redirectTo({
            //  url: '/pages/login/login'
            //})
            //}
          })
        }
      }
    })
  }
})