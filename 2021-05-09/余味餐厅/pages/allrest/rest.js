// pages/allrest/rest.js
var URL = 'https://www.youyuwei.com/apiweb/xcxrest?list=city&cityid=';
Page({
  data: {
    dest: 0,
    left: 0
  },
  onLoad: function (options) {
    var share = options.share;
    var that = this;
    var id = options.id;
    var labelid = options.labelid;
    //初始加载动画
    wx.showToast({
      title: '加载中...',
      icon: 'loading',
      duration: 8000
    });
    //判断入口
    if (labelid == undefined) {
      wx.request({
        url: URL + id,
        method: 'GET',
        success: function (res) {
          wx.hideToast();//隐藏加载动画
          var cityName = res.data.data.cityinfo.name;
          var content = res.data.data.label;
          var restList = res.data.data.list;
          var len = restList.length;
          var firstid = content[0].labelid;
          that.setData({
            cityName: cityName,
            content: content,
            showRest: restList,
            cityid: id,
            len: len,
            share: share,
            select: firstid
          });
          //动态设置导航栏标题
          wx.setNavigationBarTitle({
            title: cityName + '餐厅',
          })
        },
      })
    } else {
      wx.request({
        url: 'https://www.youyuwei.com/apiweb/xcxrest?list=city&cityid=' + options.cityid + '&labelid=' + labelid + '',
        data: {},
        method: 'GET',
        success: function (res) {
          wx.hideToast();
          var cityName = res.data.data.cityinfo.name;
          var content = res.data.data.label;
          var restList = res.data.data.list;
          var len = restList.length;
          that.setData({
            cityName: cityName,
            content: content,
            showRest: restList,
            cityid: options.cityid,
            len: len,
            share: share,
            select: labelid
          });
          wx.setNavigationBarTitle({
            title: cityName + '餐厅',
          })
        }
      })
    }
  },
  changeContent: function (e) {
    var that = this;
    var index = e.target.dataset.index;
    var labelid = e.target.id;
    var cityid = this.data.cityid;
    wx.showToast({
      title: '加载中...',
      icon: 'loading',
      duration: 8000
    });
    this.setData({
      select: labelid,
    });
    wx.request({
      url: 'https://www.youyuwei.com/apiweb/xcxrest?list=city&cityid=' + cityid + '&labelid=' + labelid + '',
      data: {},
      method: 'GET',
      success: function (res) {
        wx.hideToast();
        var restList = res.data.data.list;
        var len = restList.length;
        that.setData({
          showRest: restList,
          len: len
        });
      }
    })
  },
  //分享代码
  onShareAppMessage: function () {
    var id = this.data.cityid;
    var cityname = this.data.cityName;
    return {
      title: "余味全球美食",
      desc: cityname + "餐厅",
      path: '/pages/allrest/rest?id=' + id + '&share=1'
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