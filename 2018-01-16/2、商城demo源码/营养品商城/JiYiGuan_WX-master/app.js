const constant = require("./util/constant.js");

App({
  onLaunch: function () {
    wx.getSystemInfo({
      success: function (res) {
        this.globalData.window_width = res.windowWidth;
        this.globalData.window_height = res.windowHeight;
      }.bind(this)
    });

    wx.login({
      success: function (res) {
        if (res.code) {
          wx.request({
            url: constant.host + '/wechat/api/openid?js_code=' + res.code,
            data: {

            },
            success: function (res) {
              this.globalData.open_id = res.data.openid;
            }.bind(this),
            fail: function (res) {

            }
          });

          wx.getUserInfo({
            success: function (res) {
              this.globalData.userInfo = res.userInfo;
              var userInfo = res.userInfo
            }.bind(this)
          });
        }
      }.bind(this)
    });
  },
  globalData: {
    userInfo: {},
    window_width: 0,
    window_height: 0,
    open_id: ''
  }
})