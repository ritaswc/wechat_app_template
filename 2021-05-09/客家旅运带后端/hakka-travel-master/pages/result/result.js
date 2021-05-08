//result.js
//获取应用实例
const app = getApp()
Page({
  data: {
    
  },
 
  onLoad: function () {
    var that = this
  },
  goPay() {
    wx.request({
      url: 'https://',
      data: {},
      method: 'POST', // OPTIONS, GET, HEAD, POST, PUT, DELETE, TRACE, CONNECT
      header: {}, // 设置请求的 header
      success: function(res){
        // success
      },
      fail: function() {
        // fail
      },
      complete: function() {
        // complete
      }
    })
  },
  orderDetail() {
  	wx.navigateTo({
  		url: '../detail/detail'
  	})
  }
})
