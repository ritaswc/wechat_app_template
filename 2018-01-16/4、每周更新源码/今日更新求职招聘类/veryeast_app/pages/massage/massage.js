// pages/massage/massage.js
let requireMassage = require('../../utils/wxrequire').requireMassage;
Page({
  data:{
    massage_num:'',
    massage_look:''
  },
  onLoad:function(options){
    // 页面初始化 options为页面跳转所带来的参数
    let user_ticket = wx.getStorageSync('user_ticket');
    wx.showNavigationBarLoading(); //顶部显示加载loading
    let _that = this;
    requireMassage( '/user/status', user_ticket,function(res){
        let status = res.data.status;
        let data = res.data.data || [];
        if(status == 1){
            _that.setData({
                massage_num:data.unread_message_num
            })
            wx.hideNavigationBarLoading()
        }else{
          console.log( res )
          let err = res.data.errMsg;
          wx.showModal({
          title: '失败',
          showCancel:false,
          content: err,
          success: function(res) {
              }
          })
        }
    },function(){
      console.log( '接口调用失败' )
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
  },
  lookme:()=>{
    wx.navigateTo({
      url: '../lookMe/lookMe'
    })
  },
  systemMassage:()=>{
    wx.navigateTo({
      url: '../systemMassage/systemMassage'
    })
  },
  onPullDownRefresh:function(){  //上拉刷新 在josn中开启;
      let _that = this;
      let user_ticket = wx.getStorageSync('user_ticket');
      requireMassage( '/user/status', user_ticket,function(res){
          let status = res.data.status;
          let data = res.data.data || [];
          if(status == 1){
              wx.stopPullDownRefresh()
              _that.setData({
                  massage_num:data.unread_message_num
              })
          }else{
            console.log( res )
            let err = res.data.errMsg;
            wx.showModal({
            title: '失败',
            showCancel:false,
            content: err,
            success: function(res) {
                }
            })
          }
      },function(){
        console.log( '接口调用失败' )
      })
  },
})