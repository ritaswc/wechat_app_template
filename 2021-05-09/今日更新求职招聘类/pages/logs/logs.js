var app = getApp();
const url = require('../../utils/requireurl.js').url;
Page({
  data:{
    hidden_password:true,
    close_img:false,
    loding:false
  },
  onLoad:function(options){
    // 页面初始化 options为页面跳转所带来的参数
    
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
  change_password:function(){
      this.setData({
          hidden_password : !this.data.hidden_password,
          close_img : !this.data.close_img
      })
  },
  log:function(e){
      wx.showNavigationBarLoading() //顶部显示加载动画
      let username = e.detail.value.username;
      let password = e.detail.value.password;
      let user_id = wx.getStorageSync('user_id');
      // console.log( username,password )
      wx.request({
        url: url+'/weChat/wechat-applet/binding',
        header: {
           'content-type': 'application/x-www-form-urlencoded'
        },
        data: {
          'username':username,
          'password':password,
          'user_id':user_id
        },
        method: 'POST',
        success: function(res){
          console.log( res )
          let status = res.data.status;
          let data = res.data.data;
          if( status == 1 ){  //存储登录状态
              wx.setStorage({ key:"user_id", data: data.user_id });
              wx.setStorage({ key:"user_ticket", data: data.user_ticket });
              wx.setStorage({ key:"user_name", data: data.user_name });
              wx.setStorage({ key:"is_binding", data: data.is_binding });
              wx.navigateBack({
                delta: 1
              })
              wx.hideNavigationBarLoading()
          }else{
              wx.hideNavigationBarLoading()
              let err = res.data.errMsg;
              wx.showModal({
              title: '失败',
              showCancel:false,
              content: err,
              success: function(res) {
                  }
              })
          }
        },
        fail: function() {
          console.log( "调用登录接口失败" )
        }
      })
    }
})