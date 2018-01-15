// pages/review/review.js
var app=getApp()
Page({
  data: {
    primarySize:'default',
    reviewData: []
  },
  notPass:function(e){
    console.log("notpass")
    var index=e.currentTarget.dataset.id;
    var listdata=this.data.reviewData
    wx.request({
      url: `${app.globalData.API_URL}/message/`+listdata[index].id,
      data: {},
      method: 'DELETE', // OPTIONS, GET, HEAD, POST, PUT, DELETE, TRACE, CONNECT
      // header: {}, // 设置请求的 header
      success: function(res){
        console.log(res)
      },
      fail: function() {
        // fail
      },
      complete: function() {
        // complete
      }
    })
  },
  pass:function(e){
    console.log(e)
    var that=this
    var index=e.currentTarget.dataset.id;
    var listdata=this.data.reviewData
    console.log("pass")
    wx.request({
      url: `${app.globalData.API_URL}/message`,
      data: {
        groupid:listdata[index].last_toast,
        userid:listdata[index].from_uid,
        adminid:listdata[index].to_uid,
        shop_name:listdata[index].type

      },
      method: 'POST', // OPTIONS, GET, HEAD, POST, PUT, DELETE, TRACE, CONNECT
      // header: {}, // 设置请求的 header
      success: function(res){
        // success
        console.log(res)
        if(res==1){
          that.onReady();
        }
      },
      fail: function() {
        // fail
      },
      complete: function() {
        // complete
      }
    })

  },
  onLoad: function (options) {
    // 页面初始化 options为页面跳转所带来的参数
  },
  onReady: function () {
    // 页面渲染完成
    var that=this
    var loginInfo = wx.getStorageSync('login')
    wx.request({
      url: `${app.globalData.API_URL}/message?id=`+loginInfo.mid,
      data: {},
      method: 'GET', // OPTIONS, GET, HEAD, POST, PUT, DELETE, TRACE, CONNECT
      // header: {}, // 设置请求的 header
      success: function(res){
        // success
        console.log(res)
        that.setData({
          reviewData:res.data
        })

      },
      fail: function() {
        // fail
      },
      complete: function() {
        // complete
      }
    })
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