

var app = getApp()

Page({
  data: {
    myUserInfo: null,
    hasLogin: false
  },

  updateUserInfo: function(myUserInfo) {
    this.setData({
      myUserInfo: myUserInfo
    })
  },

  onLoad: function() {
    this.getUserInfo();
    app.getUserInfo(this.updateUserInfo);
    this.setData({
      hasLogin: app.globalData.hasLogin
    })
    this.getUserInfo();
  },
  onShow: function() {
    this.getUserInfo();
    app.getUserInfo(this.updateUserInfo);
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

  submitForm: function(e) {
    var self = this
    var form_id = e.detail.formId
    var formData = e.detail.value

    console.log('form_id is:', form_id)

    self.setData({
      loading: true
    })

    app.getUserOpenId(function(err, openid) {
      if (!err) {
        wx.request({
          url: templateMessageUrl,
          method: 'POST',
          data: {
            form_id,
            openid,
            formData
          },
          success: function(res) {
            console.log('submit form success', res)
            wx.showToast({
              title: '发送成功',
              icon: 'success'
            })
            self.setData({
              loading: false
            })
          },
          fail: function({errMsg}) {
            console.log('submit form fail, errMsg is:', errMsg)
          }
        })
      } else {
        console.log('err:', err)
      }
    })
  }
})


