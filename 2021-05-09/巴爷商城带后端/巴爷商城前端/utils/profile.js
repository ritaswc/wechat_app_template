const app = getApp()

export function getZichanSlides (resolve) {
  app.authRequest({
    url: `${app.globalData.API_URL}/my_assets`,
    header: { 'Content-Type': 'application/json'},
    success: resolve,
    fail: function(){}
  })
}

export function postCustomerInfo (data, cb) {
  data['code'] = app.globalData.code
  data['encrypted'] = app.globalData.encrypted
  app.request({
    url: `${app.globalData.API_URL}/sessions/login`,
    method: 'POST',
    data: data,
    success: function(res) {
      if (res.data.code === 4) {
        return
      } else if (parseInt(res.statusCode) === 403) {
        wx.showModal({
          title: '错误',
          content: `${res.data.msg}`,
          showCancel: false,
          success: function(res) {
          }
        })
        wx.hideToast()
        return
      }
      var pages = getCurrentPages()
      pages[pages.length-1].setData({needBindMobile: false})
      app.globalData.currentCustomer = res.data.customer
      app.globalData.token = res.data.token
      wx.setStorage({
        key: 'userToken',
        data: res.data.token
      })
      typeof cb == "function" && cb(app.globalData.currentCustomer)
    },
    fail: function(res) {
    }
  })
}

export function getPassCode (mobile, cb) {
  app.request({
    url: `${app.globalData.API_URL}/send_validation_code/send_message`,
    header: { 'Content-Type': 'application/json'},
    data: {
      mobile: mobile
    },
    success: cb,
    fail: function(res) {
    }
  })
}

