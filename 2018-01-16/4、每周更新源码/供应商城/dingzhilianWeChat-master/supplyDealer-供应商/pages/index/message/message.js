//获取应用实例
var app = getApp();

Page({
  data: {
    imageCtx: app.globalData.imageCtx,
    list: [],
    page: 1,
    info: '加载中...',
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

    that.getMessage();
  },
  updateMessage: function (e) {
    var that = this;
    that.setData({
      hidden: false,
      info: '保存中...'
    })
    console.log(e)
    var params = {}, relevance = e.target.dataset.relevance, adminObj = app.globalData.adminObj;
    params.id = e.target.dataset.id;
    params.friends_id = e.target.dataset.friendsid;
    params.state = e.target.dataset.state;
    params.phone = adminObj.phone;
    params.password = app.globalData.password;
    params.sessionId = adminObj.sessionId;
    if (relevance != '') {
      params.relevance = relevance;
    }

    wx.request({
      url: app.globalData.requestUrl + "weixinMerchant/updateMessage",
      data: params,
      success: function (res) {
        that.setData({
          hidden: false
        })
        if (res.data.code == '0') {
          that.getMessage();//重新加载消息页面
        } else {
          app.noLogin(res.data.msg);
        }
      },
      fail: function (res) {
        that.setData({
          hidden: true
        })
        app.warning("服务器响应超时");
      }
    })
  },
  scroll: function (e) {
    //  该方法绑定了页面滚动时的事件，这里记录了当前的position.y的值,为了请求数据之后把页面定位到这里来。
    this.setData({
      scrollTop: e.detail.scrollTop
    });
  },
  getMessage: function () {
    var that = this;
    that.setData({
      list: [],
      page: 1,
      emptyShow: false,
      hidden: false,
      scrollTop: 0
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
    params.phone = adminObj.phone;
    params.sessionId = adminObj.sessionId;
    params.password = app.globalData.password;
    params.pageNumber = page;

    wx.request({
      url: app.globalData.requestUrl + "weixinMerchant/getMessage",
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
      }
    })
  }
})