//index.js 
//获取应用实例

var app = getApp()
Page({
  data: {
    phone: '',
    password: ''
  },
  phoneInput: function (e) {
    this.setData({
      phone: e.detail.value
    })
  },
  pwdInput: function (e) {
    this.setData({
      password: e.detail.value
    })
  },
  login: function () {
    var that = this, reg = /^\s*|\s*$/g;
    var phone = that.data.phone;
    var password = that.data.password;
    phone = phone.replace(reg, '');

    if (!phone && !password) {
      app.warning('手机号或密码不能为空');
      return;
    } else if (!password) {
      app.warning('请输入密码');
      return;
    } else if (!phone) {
      app.warning('请输入手机号');
      return;
    }

    wx.showToast({
      title: '登录中...',
      icon: 'loading',
      duration: 10000
    });

    //获取微信用户登录后的code
    wx.login({
      success: function (res) {
        var jscode = res.code;
        wx.request({
          url: app.globalData.requestUrl + 'weixinMerchant/login',
          data: {
            phone: phone,
            password: password,
            code: jscode
          },
          success: function (res) {
            if (res.data.code == '0') {
              var adminObj = res.data.result;
              app.globalData.adminObj = adminObj;
              app.globalData.password = password;

              //保存会话session,每次到定制链服务器请求的时候带上这个session用于校验小程序登录是否过期
              wx.setStorageSync('dealerAdminObj', adminObj);
              wx.setStorageSync('dingzhilian_pwd', password);

              wx.navigateBack({
                url: '/pages/index/index'
              })
            } else {
              wx.hideToast();
              app.warning(res.data.msg);
            }
          },
          fail: function (res) {
            wx.hideToast();
            app.warning("服务器无响应");
          }
        })
      }
    })
  }
})
