var app =getApp()
Page({
  data:{
    "text":"提现"
  },
  change:function(){
    wx.showModal({
      title: '提示',
      content: '提现申请成功，请等待复核！',
      success: function(res) {
        if (res.confirm) {
          console.log('用户点击确定')
          wx.navigateTo({
            url: '../account'
          })
        }
      }
    })
  }
})