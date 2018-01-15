var app = getApp()
Page({
  data: {
    userInfo: {}
  },
  //事件处理函数
  onLoad: function () {
    console.log('onLoad')
    var that = this
    //调用应用实例的方法获取全局数据
    app.getUserInfo(function(userInfo){
      //更新数据
      that.setData({
        userInfo:userInfo
      })
    })
  },
  // 添加按钮跳转
  add:function(){
    wx.redirectTo({
      url: '../new_task/new_task'
    })
  }
})
