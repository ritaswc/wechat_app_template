// pages/collect/collect.js
var app=getApp();
Page({
  data:{
    listgoods:[]
  },
  onLoad:function(options){
    // 页面初始化 options为页面跳转所带来的参数
    var that=this;
    var login=wx.getStorageSync('login')
    wx.request({
      url: `${app.globalData.API_URL}/mygoods?uid=`+login.mid,
      data: {},
      method: 'GET', // OPTIONS, GET, HEAD, POST, PUT, DELETE, TRACE, CONNECT
      // header: {}, // 设置请求的 header
      success: function(res){
        // success
        console.log(res)
        that.setData({
          listgoods:res.data
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
  upgood:function(e){
     var that=this
    var login=wx.getStorageSync('login')
  
    wx.request({
      url:  `${app.globalData.API_URL}/mygoods`,
      data: {
        user_id:login.mid,
        id:e.currentTarget.dataset.id,
        status:String(e.currentTarget.dataset.status)
      },
      method: 'POST', // OPTIONS, GET, HEAD, POST, PUT, DELETE, TRACE, CONNECT
      // header: {}, // 设置请求的 header
      success: function(res){
        // success
        console.log(res);
        that.onLoad()
        that.onReady()
      },
      fail: function() {
        // fail
      },
      complete: function() {
        // complete
      }
    })
  },
  delgood:function(e){
    var that=this
    console.log(e.currentTarget.dataset)
    wx.request({
      url: `${app.globalData.API_URL}/mygoods/`+e.currentTarget.dataset.id,
      data: {},
      method: 'DELETE', // OPTIONS, GET, HEAD, POST, PUT, DELETE, TRACE, CONNECT
      // header: {}, // 设置请求的 header
      success: function(res){
        // success
        console.log(res)
        that.onLoad()
      },
      fail: function() {
        // fail
      },
      complete: function() {
        // complete
      }
    })
  },
  onReady:function(){
    // 页面渲染完成
  },
  onShow:function(){
    // 页面显示
  },
  onHide:function(){
    // 页面隐藏
  },
  onUnload:function(){
    // 页面关闭
  }
})