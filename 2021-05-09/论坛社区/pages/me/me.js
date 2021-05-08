// pages/me/me.js
Page({
  data: {
    bind: false,
    accesstoken: null,
    user: null,
    loginname: null
  },
  logout: function () {
    wx.setStorage({
      key: "accesstoken",
      data: null,
      success: (res) => {
        this.setData({
          bind: false,
          accesstoken: null,
          user: null,
          loginname: null
        })
      }
    });
  },
  onLoad: function (options) {
    if (!this.data.bind) {
      this.reloadPage();
    }
  },
  onReady: function () {
    // 页面渲染完成
  },
  onShow: function () {
    // 页面显示
  },
  onHide: function () {
    // 页面隐藏
  },
  onUnload: function () {
    // 页面关闭
  },
  showDetail: function (event) {
    wx.navigateTo({
      url: '../topics/detail/detail?id=' + event.target.dataset.id
    })
  },
  reloadPage: function () {
    wx.getStorage({
      key: 'accesstoken',
      success: (res) => {
        this.setData({
          "accesstoken": res.data,
          "bind": !!res.data
        });
        //验证token
        wx.request({
          url: 'https://nutz.cn/yvr/api/v1/accesstoken',
          data: {
            "accesstoken": this.data.accesstoken,
          },
          success: (res) => {
            this.setData({
              "loginname": res.data.loginname
            });
            //todo 获取用户信息
            wx.request({
              url: 'https://nutz.cn/yvr/api/v1/user/' + this.data.loginname,
              success: (res) => {
                this.setData({
                  "user": res.data.data
                });
              }
            });
            //获取未读消息
          },
          fail: function () {//验证失败
            wx.showToast({
              title: '无效的二维码',
              icon: 'success',
              duration: 1000
            })
          }
        });
      }
    })
  },
  scan: function () {
    var that = this;
    wx.scanCode({
      success: function (obj) {
        that.setData({
          "accesstoken": obj.result,
        });
        wx.setStorage({
          key: "accesstoken",
          data: obj.result
        });
        that.reloadPage();
      },
      fail: function () {
        wx.showToast({
          title: '扫码失败',
          icon: 'success',
          duration: 1000
        })
      }
    })
  }
})