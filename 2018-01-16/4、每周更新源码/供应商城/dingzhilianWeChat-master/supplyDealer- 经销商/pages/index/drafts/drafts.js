//获取应用实例
var app = getApp();

Page({
  data: {
    imageCtx: app.globalData.imageCtx,
    list: [],
    page: 1,
    hidden: false,
    emptyShow: false,
    hasMore: true,
    scrollTop: 0,
    scrollHeight: 0
  },
  onLoad: function (option) {
    var that = this;
    wx.getSystemInfo({
      success: function (res) {
        that.setData({
          scrollHeight: res.windowHeight
        });
      }
    });

    that.searchOrders();
  },
  scroll: function (e) {
    //  该方法绑定了页面滚动时的事件，这里记录了当前的position.y的值,为了请求数据之后把页面定位到这里来。
    this.setData({
      scrollTop: e.detail.scrollTop
    });
  },
  searchOrders: function () {
    var that = this;
    that.setData({
      page: 1
    })
    that.commonSearch(that, 'one');
  },
  bindDownLoad: function () {
    var that = this;
    that.commonSearch(that, 'more');
  },
  goTop: function () {
    this.setData({
      scrollTop: 0
    });
  },
  commonSearch: function (that, difference) {
    var params = {}, page = that.data.page, adminObj = app.globalData.adminObj;
    params.pageNumber = page;
    params.phone = adminObj.phone;
    params.password = app.globalData.password;
    params.sessionId = adminObj.sessionId;
    wx.request({
      url: app.globalData.requestUrl + "weixinMerchant/getDraftOrders",
      data: params,
      success: function (res) {
        that.setData({
          hidden: true
        })
        if (res.data.code == '0') {
          var reqList = res.data.pageResults.list;
          if (reqList != null && reqList.length > 0) {
            var listNew = that.data.list.concat(reqList);
            that.setData({
              list: listNew,
              page: page + 1,
              hasMore: true,
              emptyShow: false
            })
            page++;
          } else {
            that.setData({
              hasMore: false
            })
            //one第一次加载,more为上拉加载
            if (difference == 'one') {
              that.setData({
                emptyShow: true
              })
            } else {
              that.setData({
                emptyShow: false
              })
            }
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