// pages/musteat/musteat.js
var URL = 'https://www.youyuwei.com/apiweb/xcxmustdish?id='
Page({
  data: {},
  onLoad: function (options) {
    var that = this;
    var id = options.id;
    wx.showToast({
      title: '加载中...',
      icon: 'loading',
      duration: 10000
    })
    wx.request({
      url: URL + id,
      data: {},
      method: 'GET',
      success: function (res) {
        wx.hideToast();
        var dish = res.data.data.dish;
        var list = res.data.data.list;
        var cityname = dish.cityname;
        that.setData({
          dish: dish,
          list: list,
        })
        wx.setNavigationBarTitle({
          title: cityname + '必吃美食',
          success: function (res) {
            // success
          }
        })
      },
    })
  },
})