//app.js
App({
  onLaunch: function () {
    var that = this;
    var adminObj = wx.getStorageSync('dealerAdminObj');
    var password = wx.getStorageSync('dingzhilian_pwd');
    if (adminObj && password) {
      wx.request({
        url: that.globalData.requestUrl + 'weixinMerchant/checkSession',
        data: {
          phone: adminObj.phone,
          password: password,
          sessionId: adminObj.sessionId
        },
        success: function (res) {
          if (res.data.code == '0') {
            adminObj = res.data.result;
            wx.setStorageSync('dealerAdminObj', adminObj);
            that.globalData.adminObj = adminObj;
            that.globalData.password = password;
          }
        }
      })
    }
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
            }
          })
        }
      })
    }
  },
  globalData: {
    userInfo: null,
    requestUrl: 'https://www.dingzhilian.com/beta/',
    //requestUrl: 'http://192.168.1.28:8080/',
    //定制链经销商小程序图片url
    imageCtx: 'https://www.dingzhilian.com/upload_dz/',
    //缓存信息
    adminObj: '',
    phone: '',
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
    var that = this, currpage = getCurrentPages(), index = currpage[0];
    wx.showModal({
      content: content,
      showCancel: false,
      success: function (res) {
        wx.setStorageSync('dealerAdminObj', '');
        that.globalData.adminObj = '';
        if (res.confirm) {
          var length = currpage.length;
          if (length > 1) {//如果当前页面超过1个,则后退length-1个页面到index页面
            wx.navigateBack({
              delta: length - 1,
              success: function () {
                index.login();//只能在开发者工具上执行,手机无法执行此方法
              }
            })
          } else {
            index.login();
          }
        }
      }
    })
  },
  "networkTimeout": {
    "request": 10000,
    "connectSocket": 10000,
    "uploadFile": 10000,
    "downloadFile": 10000
  }
})