var app = getApp();
Page({
  data: {
    imageCtx: app.globalData.imageCtx,
    adminDept: '',
    start: '',
    end: '',
    list: [],
    total: '',
    hidden: false,
    emptyShow: false
  },
  onLoad: function () {
    var that = this;
    that.setData({
      adminDept: app.globalData.adminObj.dept_id
    })
    that.design(that);
  },
  filtrate: function () {
    var that = this;
    that.design(that);
  },
  design: function (that) {
    that.setData({
      hidden: false
    })
    var that = this, adminObj = app.globalData.adminObj;
    wx.request({
      url: app.globalData.requestUrl + "weixinMerchant/designStatistic",
      data: {
        start: that.data.start,
        end: that.data.end,
        phone: adminObj.phone,
        password: app.globalData.password,
        sessionId: adminObj.sessionId
      },
      success: function (res) {
        that.setData({
          hidden: true
        })
        if (res.data.code == '0') {
          var map = res.data.mapResults, design = map.stats, total = map.total;
          that.setData({
            start: map.start,
            end: map.end
          })
          if (design && design.length > 0) {
            that.setData({
              list: design,
              total: total,
              emptyShow: false
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
  },
  go: function (e) {
    var that = this, start = that.data.start, end = that.data.end;
    var id = e.currentTarget.dataset.id, name = e.currentTarget.dataset.name;
    wx.navigateTo({
      url: '/pages/index/designCost_statistics/designCost_department/designCost_department?id=' + id + '&start=' + start + '&end=' + end + '&name=' + name
    })
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
})