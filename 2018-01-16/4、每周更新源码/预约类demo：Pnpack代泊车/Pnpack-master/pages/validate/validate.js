// pages/validate/validate.js
Page({
  data: {
    code: '',
    id: ''
  },
  onLoad: function (options) {
    var vm = this;
    vm.setData({
      id: options.id,
    })
  },
  bindKeyInput: function (e) {
    var value = e.detail.value
    this.setData({
      code: value
    })
  },
  verifyCode: function () {
    var id = this.data.id
    wx.request({
      url: 'https://wx.viparker.com/valetparking/api/web/index.php/order/verify-code',
      data: {
        openid: wx.getStorageSync('openid'),
        order_id: this.data.id,
        code: this.data.code
      },
      header: {
        "Content-Type": "application/x-www-form-urlencoded"
      },
      method: "POST",
      success: function (res) {
        wx.showModal({
          title: '验证通过',
          content: '验证通过！您可以放心的将钥匙交给该泊车人进行代泊。',
          confirmText: '返回详情',
          success: function (res) {
            if (res.confirm) {
              var vm = this;
              wx.navigateTo({
                url: '/pages/index/index'
              })
            }
          }
        })
      }
    });
  }
})