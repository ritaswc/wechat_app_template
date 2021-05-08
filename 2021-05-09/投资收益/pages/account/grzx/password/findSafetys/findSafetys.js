var app =getApp()
Page({
  data:{
    "text":"找回安全密码第二步"
  },
  change:function(){
      wx.showToast({
        title: '安全密码设置成功',
        icon: 'success',
        duration: 2000
        })
  }
})