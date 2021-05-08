// pages/massageContant/massageContant.js
const url = require('../../utils/requireurl.js').url;
const WxParse = require('../../wxParse/wxParse.js');
Page({
  data:{
      massage:''
  },
  onLoad:function(options){
    // 页面初始化 options为页面跳转所带来的参数
    let message_id = options.message_id;
    let user_ticket = wx.getStorageSync('user_ticket');
    let _that =this;
    wx.showToast({
        title: '加载中...',
        icon: 'loading',
        duration: 2000000
    })
    wx.request({
      url: url+'/user/message_detail',
      header: {
          'content-type': 'application/x-www-form-urlencoded'
      },
      data: {
          'message_id':message_id,
          'user_ticket':user_ticket
      },
      method: 'POST',
      success: function(res){
        let status = res.data.status;
        if( status == 1 ){
          let massage = res.data.data;
          let content = massage.content
          WxParse.wxParse('massageContent', 'html', content, _that); //解析html;
            _that.setData({
                massage:massage
            })
            wx.hideToast()
        }else{
           wx.hideToast();
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
        console.log( 'go暂不合适消息详情失败' )
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