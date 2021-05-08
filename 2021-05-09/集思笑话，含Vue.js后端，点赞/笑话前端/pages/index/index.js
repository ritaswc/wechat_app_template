//index.js
//获取应用实例
var wxParse = require('../../wxParse/wxParse')
var WXRequest = require('../../utils/util').WXRequest
var app = getApp()
Page({
  data: {
    showloading: 1,
    showmore: 0,
    count:20,
    jokes:{},
    contents:{},
    userInfo: {}
  },
  onShareAppMessage: function () {
    return {
      title: '我收集的群笑话，这个可以笑一年',
      path: '/pages/index/index',
      success: function(res) {
        // 分享成功
      },
      fail: function(res) {
        // 分享失败
      }
    }
  },
  onPullDownRefresh: function (){
    // can refresh ?
       var that = this
    wx.showLoading({
      title:"加载更多",
      icon:'success',
      duration:3000
    })
    //调用应用实例的方法获取全局数据
    wx.request({
      url:'https://jsjoke.net/api/jokes?limit=' + that.data.count,
      success: function (res){
        for (let i=0 ; i<res.data.length; i++){
          if (!res.data[i].author[0].avatar){
            res.data[i].author[0].avatar='https://jsjoke.net/static/default-img.png'
          } else if (res.data[i].author[0].avatar.slice(0,4) != 'http') {
               res.data[i].author[0].avatar = 'https://jsjoke.net' + res.data[i].author[0].avatar
          }
          wxParse.wxParse('reply' + i,'html',res.data[i].content,that);
          if (i === res.data.length - 1 ){
            wxParse.wxParseTemArray('replyTemArray','reply',res.data.length,that) 
          }
        }

        that.setData({
          jokes:res.data,
          showloading: 0,
        })
        
      }
    })
    
  },
  bindcomment: function(e) {
    var id = e.currentTarget.dataset.id
    var index = e.currentTarget.dataset.index
    var that = this
    wx.navigateTo({
      url:'../comment/index?id=' + id
    })
  },
  bindjoke: function(e) {
    var id = e.currentTarget.dataset.id
    var index = e.currentTarget.dataset.index
    var that = this
    wx.request({
      url:'https://jsjoke.net/api/jokes/' + id + '?joke=1',
      success: function (res){
        //console.log(res.data)
        that.data.jokes[index].joke = res.data.joke
        that.setData({
          jokes:that.data.jokes
        })
      }
    })
  },
  
  bindunjoke: function(e) {
    var id = e.currentTarget.dataset.id
    var index = e.currentTarget.dataset.index
    var that = this
    wx.request({
      url:'https://jsjoke.net/api/jokes/' + id + '?unjoke=1',
      success: function (res){
        that.data.jokes[index].unjoke = res.data.unjoke
        that.setData({
          jokes:that.data.jokes
        })
        
      }
    })
  },
  getusefromserver: function(e){
    wx.switchTab({
      url: '/pages/my/my',
      success: function(res){
        // success
      },
      fail: function(res) {
        // fail
      },
      complete: function(res) {
        // complete
      }
    })
  },
  onReady: function () {

  },
  lower: function(e) {
   // console.log(e)
    
    var that = this
    wx.showLoading({
      title:"加载更多",
      icon:'success',
      duration:3000
    })
    //调用应用实例的方法获取全局数据
    that.data.count += 20
    wx.request({
      url:'https://jsjoke.net/api/jokes?limit=' + that.data.count,
      success: function (res){
        for (let i=0 ; i<res.data.length; i++){
          if (!res.data[i].author[0].avatar){
            res.data[i].author[0].avatar='https://jsjoke.net/static/default-img.png'
          } else if (res.data[i].author[0].avatar.slice(0,4) != 'http') {
               res.data[i].author[0].avatar = 'https://jsjoke.net' + res.data[i].author[0].avatar
          }
          wxParse.wxParse('reply' + i,'html',res.data[i].content,that);
          if (i === res.data.length - 1 ){
            wxParse.wxParseTemArray('replyTemArray','reply',res.data.length,that) 
          }
        }

        that.setData({
          jokes:res.data,
          showloading: 0,
        })
        
      }
    })

  },
  onLoad: function () {
    console.log('onLoad')
 
    var that = this
    //调用应用实例的方法获取全局数据
    app.getUserInfo(function(userInfo){
      //更新数据
      that.setData({
        userInfo:userInfo
      })
    })
    wx.request({
      url:'https://jsjoke.net/api/jokes?limit=20',
      success: function (res){
        for (let i=0 ; i<res.data.length; i++){
          if (!res.data[i].author[0].avatar){
            res.data[i].author[0].avatar='https://jsjoke.net/static/default-img.png'
          } else if (res.data[i].author[0].avatar.slice(0,4) != 'http') {
               res.data[i].author[0].avatar = 'https://jsjoke.net' + res.data[i].author[0].avatar
          }
          wxParse.wxParse('reply' + i,'html',res.data[i].content,that);
          if (i === res.data.length - 1 ){
            wxParse.wxParseTemArray('replyTemArray','reply',res.data.length,that) 
          }
        }
        that.setData({
          jokes:res.data,
          showloading: 0
        })
      }
    })

  }
})
