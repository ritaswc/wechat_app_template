var app = getApp()

Page({
  data: {
    userInfo:null
  },

  onShow: function() {
  },

  onLoad: function() {
    var that = this
    app.getUserInfo(function(userInfo){
      that.setData({userInfo:userInfo})
    })
  }
})