var app = getApp()
Page({
  // data
  data: {
    hasUserInfo: false,
    hasLogin: false,
    // list 
    list: [
      {
        id: 'view',
        title: '个人信息',
        open: false,
        pages: ['view', 'scroll-view', 'swiper'],
        toUrl: '../home-page-my-info/home-page-my-info'
      }, {
        id: 'form',
        title: '我的投票',
        open: false,
        toUrl: '../home-page-my-vote/home-page-my-vote'
      }
    ],
  },

  onLoad: function () {
    this.setData({
      hasLogin: app.globalData.hasLogin
    })
    this.getUserInfo();
  },

  // get the user info 
  getUserInfo: function () {
    var that = this

    if (app.globalData.hasLogin === false) {
      wx.login({
        success: _getUserInfo
      })
    } else {
      _getUserInfo()
    }

    function _getUserInfo() {
      wx.getUserInfo({
        success: function (res) {
          that.setData({
            hasUserInfo: true,
            userInfo: res.userInfo
          })
          that.update()
        }
      })
    }
  },

  // login 
  login: function () {
    this.setData({
      hasLogin: true
    })
  },

  // logout 
  clear: function () {
    this.setData({
      hasUserInfo: false,
      userInfo: {}
    })
  }
})
