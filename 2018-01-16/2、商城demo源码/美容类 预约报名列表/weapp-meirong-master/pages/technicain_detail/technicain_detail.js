var app = getApp()
Page( {
  data: {
  },
  onLoad: function () { 
  },
  // 预定
  bookTap:function(){
    wx.navigateTo({
      url:'../book/book'
    })
  }
})