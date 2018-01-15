var wxCharts = require('../wxcharts-min.js');
var app = getApp();
Page({
  /*时间选择器*/
  data: {
    imageCtx: app.globalData.imageCtx,
    start: '',
    end: '',
    width: 320,
    emptyShow: false
  },
  bindStartChange: function (e) {
    this.setData({
      start: e.detail.value
    })
  },
  bindEndChange: function (e) {
    this.setData({
      end: e.detail.value
    })
  },
  onLoad: function () {
    var that = this;
    wx.getSystemInfo({
      success: function (res) {
        that.setData({
          width: res.windowWidth
        });
      }
    });
    that.sourceStatistic(that);
  },
  filtrate: function () {
    var that = this;
    that.sourceStatistic(that);
  },
  sourceStatistic: function (that) {
    var params = {}, adminObj = app.globalData.adminObj;
    params.start = that.data.start;
    params.end = that.data.end;
    params.phone = adminObj.phone;
    params.password = app.globalData.password;
    params.sessionId = adminObj.sessionId;

    wx.request({
      url: app.globalData.requestUrl + "weixinMerchant/orderSourceStatistic",
      data: params,
      success: function (res) {
        if (res.data.code == '0') {
          var mapResults = res.data.mapResults, reqList = mapResults.source;
          that.setData({
            start: mapResults.start,
            end: mapResults.end
          })
          if (reqList != null && reqList.length > 0) {
            that.setData({
              emptyShow: false
            })
            new wxCharts({
              canvasId: 'pieCanvas',
              type: 'pie',
              series: reqList,
              width: that.data.width,
              height: 300,
              dataLabel: true
            });
          } else {
            that.setData({
              emptyShow: true
            })
          }
        } else {
          app.noLogin(res.data.msg);
        }
      },
      fail: function (res) {
        app.warning("服务器无响应");
      }
    })
  }
})


