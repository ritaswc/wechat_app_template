// pages/scan/scan.js
Page({
  data: {
    isLogin: false
  },
  /**
   * 页面加载
   * optins url 参数
   */
  onLoad: function (options) {
    if (wx.getStorageSync('cookie')) {
      this.setData({
        isLogin: true
      })
    }
  },
  /**
   * 页面渲染完成
   */
  onReady: function () {
  },
  /**
   * 页面显示
   */
  onShow: function () {
  },
  /**
   * 页面隐藏
   */
  onHide: function () {
  },
  /**
   * 页面关闭
   */
  onUnload: function () {
  },
  /**
   * 分享
   */
  onShareAppMessage: function () {
    return {
      title: '黑客派社区『书单』共享计划',
      desc: ' 书单是黑客派社区的一个纸质书共享活动，所有书均来自捐赠... ',
      path: '/pages/scan/scan'
    }
  },
  /**
   * 跳转到登录页面
   */
  goLogin: function () {
    wx.redirectTo({
      url: '../login/login'
    })
  },
  /**
   * 扫码
   */
  scan: function () {
    wx.scanCode({
      success: (res) => {
        if (res.errMsg !== 'scanCode:ok') {
          wx.showToast({
            title: res.errMsg,
            icon: 'loading',
            duration: 8000
          })
          return false;
        }

        if (res.scanType !== 'EAN_13') {
          wx.showToast({
            title: '我们需要的是 ISBN 编码',
            icon: 'loading',
            duration: 8000
          })
          return false;
        }

        wx.navigateTo({
          url: '../share/share?ISBN=' + res.result
        })
      }
    })
  },
  /**
   * 登出
   */
  logout: function () {
    wx.removeStorage({
      key: 'cookie',
      success: function (res) {
        wx.redirectTo({
          url: '../login/login'
        })
      }
    })
  }
})