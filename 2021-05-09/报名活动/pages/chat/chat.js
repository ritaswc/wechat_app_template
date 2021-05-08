// pages/chat/chat.js
Page({
  data:{},
  onLoad:function(options){
    var title = options.title
    wx.setNavigationBarTitle({
      title: title
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