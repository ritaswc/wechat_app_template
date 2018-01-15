var app = getApp()
Page( {
  data: {
  },
  onLoad: function (options) {
    this.setData({
      artype:options.artype
    })    
  },
  getLocation:function(){
    wx.navigateTo({
      url:'../location/location'
    })
  },
  bookTap:function(){
    wx.navigateTo({
      url:'../book/book'
    })
  }
})