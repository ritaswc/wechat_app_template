//index.js
//获取应用实例
var app = getApp()
Page({
  data: {
    motto: '',
    userInfo: {}
  },
  //事件处理函数
  goAddcar: function() {
    wx.navigateTo({
      url: '../addcar/addcar'
    })
  },
  onShow: function() {
    var carData = wx.getStorageSync('allCarData');
    this.setData({
      carData: carData
    });
  },
  goCardetail: function(e) {
    wx.navigateTo({
      url: '../cardetail/cardetail'
    })
  },
  phone: function(e) {
    var tel = e.target.dataset.tel;
    wx.makePhoneCall({
      phoneNumber: tel,
      success: function(res) {
        // success
      }
    })
  },
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
  }
})
