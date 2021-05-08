// pages/shenghuo/ttPrize/ttPrize.js
const config = require('../../../config')

Page({
  data: { 
    //ttPrizeList:[]
  },
  onttPrizeCellTap:function() {


  },
  onLoad: function (options) {
    // 页面初始化 options为页面跳转所带来的参数
    var that = this
    wx.request({
      url: config.ttPrizeUrl,
      data: {
        "pageNum": 1,
        "limit": 20,
        "typeId": "3"
      },
      method: 'POST', // OPTIONS, GET, HEAD, POST, PUT, DELETE, TRACE, CONNECT
      // header: {}, // 设置请求的 header
      success: function (res) {
        // success
        
        that.setData({ttPrizeList:res.data.data.categoryObj})
        //console.log(that.data.ttPrizeList)
      },
      fail: function (res) {
        // fail
      },
      complete: function (res) {
        // complete
      }
    })
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
  }
})