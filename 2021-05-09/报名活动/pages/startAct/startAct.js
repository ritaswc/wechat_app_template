// pages/startAct/startAct.js
Page({
  data:{},
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
  uploadIMG:function(){
    wx.uploadFile({
      url: 'http://www.secretcastle.cn', //仅为示例，非真实的接口地址
      filePath: '',
      name: 'file',
      formData:{
        'user': 'test'
      },
      success: function(res){
        var data = res.data
        //do something
      }
    })
  }
})