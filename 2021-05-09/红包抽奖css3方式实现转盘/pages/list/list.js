//index.js
//获取应用实例
var app = getApp()
Page({
  data: {
    awardsList: {},
    userInfo: {}
  },
  //事件处理函数
  gotoLottery: function() {
    wx.redirectTo({
      url: '../canvas/canvas'
    })
  },
  onLoad: function () {
    var that = this
    var list = wx.getStorageSync('winAwards') || {data:[]}

    if (list && list.data && list.data.length > 0) {
      list = list.data
    }else {
      list = []
    }

    //调用应用实例的方法获取全局数据
    app.getUserInfo(function(userInfo){
      //更新数据
      that.setData({
        userInfo:userInfo,
        awardsList: list || []
      })
    })
  }
})
