var app =getApp()
Page({
  data:{
    "text":"修改登录密码"
  },
  change:function(){
      wx.showToast({
        title: '修改成功',
        icon: 'success',
        duration: 2000
        })
  }
})