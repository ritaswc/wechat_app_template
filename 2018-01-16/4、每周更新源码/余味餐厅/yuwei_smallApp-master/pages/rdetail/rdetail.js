// pages/rdetail/rdetail.js
var URL = "https://www.youyuwei.com/apiweb/xcxrest?restid=";
Page({
  data: {
    mark: false,
  },
  onLoad: function (options) {
    var share = options.share;
    var id = options.id;
    var name = options.name;
    var that = this;
    wx.showToast({
      title: '加载中...',
      icon: 'loading',
      duration: 1000
    });
    wx.request({
      url: URL + id,
      data: {},
      method: 'GET',
      success: function (res) {
        wx.hideToast();
        var info = res.data.data.restinfo;
        var list = res.data.data.list;
        var desc = info.desc;
        that.setData({
          rest: info,
          list: list,
          lat: info.lat,
          lng: info.lng,
          name: name,
          numbers: desc.length,
          id: id,
          share: share

        })
      },
    })
    wx.setNavigationBarTitle({
      title: name + '餐厅',
    })
  },
  showMore: function () {
    var mark = this.data.mark;
    if (!mark) {
      this.setData({
        mark: true
      })
    } else {
      this.setData({
        mark: false,
      })
    }

  },
  call: function (e) {
    var num = e.target.id;
    wx.makePhoneCall({
      phoneNumber: num,
      success: function (res) {
        // success
      }
    })
  },
  watchAddress: function () {
    var lat = parseFloat(this.data.lat);
    var lng = parseFloat(this.data.lng);
    var rest = this.data.rest;
    wx.openLocation({
      latitude: lat, // 纬度，范围为-90~90，负数表示南纬
      longitude: lng, // 经度，范围为-180~180，负数表示西经
      scale: 28, // 缩放比例
      name: rest.name, // 位置名
      address: rest.address, // 地址的详细说明
      success: function (res) {
        // success
      },
    })
  },
  onShareAppMessage: function () {
    var id = this.data.id;
    var name = this.data.name;
    var title = this.data.rest;
    return {
      title: title.name,
      path: '/pages/rdetail/rdetail?id=' + id + '&name=' + name + '&share=1'
    }
  },
  share: function () {
    wx.redirectTo({
      url: '/pages/index/index',
      success: function (res) {
        // success
      }
    })
  }
})