var wxCharts = require('../wxcharts-min.js');
var app = getApp();
Page({
  /*时间选择器*/
  data: {
    start: '',
    end: '',
    width: 320
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
    that.salesStatistic(that, 1);
    that.salesStatistic(that, 2);
    that.salesStatistic(that, 3);
  },
  filtrate: function () {
    var that = this;
    that.salesStatistic(that, 1);
    that.salesStatistic(that, 2);
    that.salesStatistic(that, 3);
  },
  salesStatistic: function (that, num) {
    var params = {}, adminObj = app.globalData.adminObj;
    params.number = num;
    params.start = that.data.start;
    params.end = that.data.end;
    params.phone = adminObj.phone;
    params.password = app.globalData.password;
    params.sessionId = adminObj.sessionId;

    var unit = '';
    if (num == 1) {
      unit = '个';
    } else if (num == 2) {
      unit = '件';
    } else {
      unit = '元';
    }

    wx.request({
      url: app.globalData.requestUrl + "weixinMerchant/orderSellStatisticForMonth",
      data: params,
      success: function (res) {
        if (res.data.code == '0') {
          var mapResults = res.data.mapResults;
          that.setData({
            start: mapResults.start,
            end: mapResults.end
          })
          new wxCharts({
            canvasId: 'columnCanvas' + num,
            type: 'column',
            legend: false,
            categories: mapResults.date,
            series: [{
              data: mapResults.count
            }],
            yAxis: {
              min: 0,
              title: '单位(' + unit + ')',
              format: function (val) {
                return val;
              }
            },
            width: that.data.width,
            height: 220
          });
        } else {
          if (num == 3) {
            app.noLogin(res.data.msg);
          }
        }
      },
      fail: function (res) {
        if (num == 3) {
          app.warning("服务器无响应");
        }
      }
    })
  }
})


