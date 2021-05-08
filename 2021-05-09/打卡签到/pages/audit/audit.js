// pages/audit/audit.js
Page({
  data:{
    winHeight: '',
    winWidth: '',
    message: '',
    companyName: '',
  },
  onLoad:function(options){
      var that=this;
    // 获取系统信息 
    wx.getSystemInfo({
      success: function (res) {
        that.setData(
          {
            winWidth: res.windowWidth,
            winHeight: res.windowHeight,
            message: options.message,
            companyName: options.companyName,
          });
      }
    });
  },
})