// pages/dish/dish.js
Page({
  data: {},
  onLoad: function (options) {
    var that = this;
    var id = options.id;
    var share = options.share;
    wx.showToast({
      title: '加载中...',
      icon: 'loading',
      duration: 8000
    });
    wx.request({
      url: 'https://www.youyuwei.com/apiweb/xcxdish?id=' + id + '',
      data: {},
      method: 'GET',
      success: function (res) {
        console.log(res)
        wx.hideToast();
        var obj = res.data.data;
        var name = obj.name;
        that.setData({
          obj: obj,
          id: id,
          share: share,
        })
        wx.setNavigationBarTitle({
          title: name,
        })
      }
    })
  },
  onShareAppMessage: function () {
    var id = this.data.id;
    var obj = this.data.obj;
    return {
      title: obj.name,
      path: '/pages/dish/dish?id=' + id + '&share=1'
    }
  },
  share: function () {
    wx.redirectTo({
      url: '/pages/index/index',
      success: function (res) {
        // success
      },
    })
  }
})