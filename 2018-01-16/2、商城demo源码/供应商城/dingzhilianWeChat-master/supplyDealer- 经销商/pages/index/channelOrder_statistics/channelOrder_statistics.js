//获取应用实例
var app = getApp();

Page({
  data: {
    imageCtx: app.globalData.imageCtx,
    adminDept: '',
    start: '',
    end: '',
    list: [],
    orderType: 'normal',
    typeList: [
      { orderType: 'normal', name: '正常订单' },
      { orderType: 'sample', name: '样衣订单' },
      { orderType: 'sale', name: '售后订单' }
    ],
    state: 1,
    hidden: false,
    emptyShow: false
  },
  onLoad: function (option) {
    var that = this;
    that.setData({
      adminDept: app.globalData.adminObj.dept_id
    })
    console.log(option)
    if (option.time != undefined) {//从基础信息统计跳转过来携带的日期
      that.setData({
        start: option.time,
        end: option.time
      })
    }
    that.commonSearch(that);
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
  manageerStatistic: function (e) {
    var that = this;
    if (e != undefined) {
      var orderType = e.currentTarget.dataset.type;
      if (orderType != undefined) {
        that.setData({
          orderType: orderType
        })
      }
    }
    that.setData({
      list: [],
      emptyShow: false,
      hidden: false,
    })
    that.commonSearch(that);
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
  go: function (e) {
    var that = this, start = that.data.start, end = that.data.end, orderType = that.data.orderType;
    var id = e.currentTarget.dataset.id, name = e.currentTarget.dataset.name;
    wx.navigateTo({
      url: '/pages/index/channelOrder_statistics/department/department?id=' + id + '&start=' + start + '&end=' + end + '&name=' + name + '&orderType=' + orderType
    })
  },
  commonSearch: function (that) {
    var params = {}, page = that.data.page, adminObj = app.globalData.adminObj;
    params.order_type = that.data.orderType;
    params.start = that.data.start;
    params.end = that.data.end;
    //params.pageNumber = page;
    params.phone = adminObj.phone;
    params.password = app.globalData.password;
    params.sessionId = adminObj.sessionId;

    wx.request({
      url: app.globalData.requestUrl + "weixinMerchant/manageerStatistic",
      data: params,
      success: function (res) {
        that.setData({
          hidden: true
        })
        if (res.data.code == '0') {
          var map = res.data.mapResults, manageer = map.stats, total = map.total;
          that.setData({
            start: map.start,
            end: map.end
          })
          if (manageer && manageer.length > 0) {
            for (var i in manageer) {//毛利计算保留2位小数
              var ord = manageer[i];
              ord.profit = (ord.predict_receive - ord.predict_give - ord.predict_pay).toFixed(2);
            }
            total.profit = (total.predict_receive - total.predict_give - total.predict_pay).toFixed(2);
            that.setData({
              list: manageer,
              total: total
            })
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
        that.setData({
          hidden: true
        })
        app.warning("服务器无响应");
      }
    })
  }
})