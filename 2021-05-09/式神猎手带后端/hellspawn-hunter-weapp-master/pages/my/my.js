// pages/my/my.js
Page({
  data:{
    "searchRecord": []
  },
  onLoad:function(options){
    // 页面初始化 options为页面跳转所带来的参数
  },
  onReady:function(){
    // 页面渲染完成
  },
  onShow:function(){
    // 页面显示
    var context = this;
    wx.getStorage({
      key: 'search_record',
      success: function(res){
        // success
        if(res){
          context.setData({
            searchRecord: res.data
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
  },
  onHide:function(){
    // 页面隐藏
  },
  onUnload:function(){
    // 页面关闭
  }
})