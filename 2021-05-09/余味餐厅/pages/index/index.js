var app = getApp();
var num;
Page({
  data: {
    share: 1,
    flag: false,
    mark: true
  },
  onLoad: function (options) {
    var that = this;
    var id = options.id;
    var cityname = options.name;
    var shareurl = 'https://www.youyuwei.com/apiweb/xcxcity?lat=' + lat + '&lng=' + long + '';
    wx.showToast({
      title: '加载中...',
      icon: 'loading',
      duration: 1000
    });
    if (id == undefined) {
      wx.getLocation({
        type: 'wgs84',
        success: function (res) {
          wx.hideToast();
          var lat = res.latitude; //维度
          var long = res.longitude //经度
          wx.request({
            url: 'https://www.youyuwei.com/apiweb/xcxcity?lat=' + lat + '&lng=' + long + '',
            method: 'GET',
            success: function (res) {
              var arr = res.data.data.list;
              var first = arr[0];
              var second = arr[1];
              var three = arr[2];
              var name = first.content.name;
              var content = second.content.length;
              var len = first.content.labellist.length;
              var tshow = three.content.length;
              var cityId = first.content.id;
              if (content <= 3) {
                num = 0;
              } else {
                if (content % 3 == 0) {
                  num = 0
                }
                if (content % 3 == 1) {
                  num = 1
                }
                if (content % 3 == 2) {
                  num = 2
                }
              }
              that.setData({
                first: first,
                second: second,
                ven: content,
                three: three,
                len: len,
                ren: tshow,
                cityname: name,
                cityId: cityId,
                shareurl: shareurl,
                num: num
              })
            }
          })
        },
      })
    } else {
      var lat = "";//维度
      var long = "";//经度
      wx.request({
        url: shareurl+'&id=' + id + '',
        method: 'GET',
        success: function (res) {
          wx.hideToast()
          var arr = res.data.data.list;
          var first = arr[0];
          var second = arr[1];
          var three = arr[2];
          var content = second.content.length;
          var len = first.content.labellist.length;
          var tshow = three.content.length;
          if (content <= 3) {
            num = 0;
          } else {
            if (content % 3 == 0) {
              num = 0
            }
            if (content % 3 == 1) {
              num = 1
            }
            if (content % 3 == 2) {
              num = 2
            }
          }
          that.setData({
            first: first,
            second: second,
            three: three,
            ven: content,
            ren: tshow,
            len: len,
            num: num,
            cityId: id,
            cityname: cityname,
            shareurl: shareurl
          })
        }
      })
    }

  },
  toggle: function (e) {
    var mark = this.data.mark;
    if (mark) {
      this.setData({
        flag: true,
        mark: false
      })

    } else {
      this.setData({
        flag: false,
        mark: true
      })
    }
  },
  search: function () {
    wx.redirectTo({
      url: '../list/list',
    })
  },
  onShareAppMessage: function () {
    var cityname = this.data.cityname;
    var id = this.data.cityId;
    return {
      title: "余味全球美食",
      desc: cityname + "美食推荐",
      path: '/pages/index/index?id=' + id + '&name=' + cityname + ''
    }
  },
})