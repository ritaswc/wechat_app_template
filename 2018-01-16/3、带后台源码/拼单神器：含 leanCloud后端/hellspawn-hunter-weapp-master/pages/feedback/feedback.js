// pages/feedback/feedback.js
var http = require("../../utils/http.js");
Page({
  data:{
    isAdvice: false,
    content: ""
  },
  onLoad:function(options){
    // 页面初始化 options为页面跳转所带来的参数
    if(options.type==2){
      this.setData({
        isAdvice: true
      })
    }else{
      this.setData({
        sid: options.id,
        sceneName: options.name
      })
    }
  },
  bindTextArea: function(e){
    this.setData({
      content: e.detail.value
    })
  },
  bindRadio: function(e){
    if(e.detail.value==1){
      this.setData({
        isAdvice: false
      })
    }else{
      this.setData({
        isAdvice: true
      })
    }
  },
  bindSubmit: function(){
    if(this.data.content){
      var context = this;
      var url = http.generateUrl('api/v1/feedback');
      wx.showToast({
        title: '提交中',
        icon: 'loading',
        duration: 100000
      });
      wx.request({
        url: url,
        data: {
          is_advice: context.data.isAdvice,
          scene_id: context.data.sid,
          content: context.data.content
        },
        method: 'POST', // OPTIONS, GET, HEAD, POST, PUT, DELETE, TRACE, CONNECT
        // header: {}, // 设置请求的 header
        success: function(res){
          // success
          wx.hideToast();
          if(res.data.status==1){
            wx.showToast({
              title: '提交成功',
              icon: 'success',
              duration: 1000,
              success: function(res){
                context.setData({
                  content: ""
                });
              },
              complete: function(e){
                wx.navigateBack({
                  delta: 1, // 回退前 delta(默认为1) 页面
                })
              }
            });
          }
        },
        fail: function() {
          // fail
        },
        complete: function() {
          // complete
        }
      })
    }
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