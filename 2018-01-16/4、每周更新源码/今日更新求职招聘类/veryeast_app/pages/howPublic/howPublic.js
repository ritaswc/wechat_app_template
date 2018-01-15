// pages/howPublic/howPublic.js
const url = require('../../utils/requireurl.js').url;
Page({
  data:{
    _public:'',
    arr:['对所有公开','只公开Email','完全保密']
  },
  onLoad:function(options){
    // 页面初始化 options为页面跳转所带来的参数
    let _public = options.public;
     this.setData({
       _public:_public
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
  data_back:function(e){
     let _status = e.currentTarget.dataset.status;
     let privacy= e.currentTarget.dataset.privacy;
     let user_ticket = wx.getStorageSync('user_ticket');
     let _that = this;
     wx.showToast({
        title: '加载中...',
        icon: 'loading',
        duration: 20000000
    })
    wx.setStorage({
      key:"privacy",
      data:_status
    })
    wx.request({
       url: url+'/resume/set_base',
       header: {
            'content-type': 'application/x-www-form-urlencoded'
        },
       data: {
         'user_ticket':user_ticket,
         'privacy':privacy
       },
       method: 'POST',
       success: function(res){
        //  console.log( res )
         let status = res.data.status;
         if( status == 1 ){
            wx.showToast({
              title: '成功',
              icon: 'success',
              duration: 800,
              success:function(){
                  wx.navigateBack()
              }
          })
          _that.setData({
            _public:status
          })
         }else{
           wx.showToast({
              title: '失败',
              icon: 'fail',
              duration: 800,
              success:function(){
                  wx.navigateBack()
              }
          })
         }
         
       },
       fail: function() {
         console.log('接口调用失败')
       }
     })
    
  }
})