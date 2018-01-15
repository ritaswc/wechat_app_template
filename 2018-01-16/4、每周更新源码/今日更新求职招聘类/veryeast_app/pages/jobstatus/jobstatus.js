// pages/jobstatus/jobstatus.js
const url = require('../../utils/requireurl.js').url;
Page({
  data:{
    status:'',
    arr:['正在找工作','我愿意考虑好的工作机会','暂时不想找工作']
  },
  onLoad:function(options){
    // 页面初始化 options为页面跳转所带来的参数
     let status = options.status;
     this.setData({
       status:status
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
     let job_status= e.currentTarget.dataset.job_status;
     let user_ticket = wx.getStorageSync('user_ticket');
     let _that = this;
     wx.showToast({
        title: '加载中...',
        icon: 'loading',
        duration: 20000000
    })
    wx.setStorage({
      key:"job_status",
      data:_status
    })
     wx.request({
       url: url+'/resume/set_base',
       header: {
            'content-type': 'application/x-www-form-urlencoded'
        },
       data: {
         'user_ticket':user_ticket,
         'job_status':job_status
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
            status:_status
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