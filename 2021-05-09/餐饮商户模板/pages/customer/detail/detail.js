var app = getApp()
Page({
  data: {
    wrapTopHeight:400,
    imgUrls:['','',''],
    scrollHeight:'0'
  },
  deleteWrapTop:function(){
      console.log(222)
      this.setData({
         wrapTopHeight:0 
      });
  },
  onLoad: function () {
      this.setScrollHeight();
  },




  // 设置混动区域高度
  setScrollHeight:function(){
    const _this = this;
    wx.getSystemInfo({
      success: function(res) {
        const scrollHeight = res.windowHeight;
        console.log(12222)
        _this.setData({
          scrollHeight:scrollHeight
        })
  }
    })
  },
})
