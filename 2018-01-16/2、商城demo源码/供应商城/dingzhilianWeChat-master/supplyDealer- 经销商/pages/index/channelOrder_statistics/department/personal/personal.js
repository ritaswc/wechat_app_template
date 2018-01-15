//获取应用实例
var app = getApp();

Page({
  data: {
    imageCtx: app.globalData.imageCtx,
    adminDept: '',
    start: '',
    end: '',
    dept_id: '',
    m_id: '',
    p_id: '',
    dept: '',
    manageer: '',
    principal: '',
    list: [],
    page: 1,
    hidden: false,
    emptyShow: false,
    hasMore: true,
    scrollTop: 0,
    scrollHeight: 0
  },
  onLoad: function (option) {
    console.log(option);
    var that = this;
    wx.getSystemInfo({
      success: function (res) {
        that.setData({
          adminDept: app.globalData.adminObj.dept_id,
          scrollHeight: res.windowHeight,
          start: option.start,
          end: option.end,
          dept_id: option.dept_id,
          m_id: option.m_id,
          p_id: option.p_id,
          dept: option.dept,
          manageer: option.manageer,
          principal: option.principal
        });
      }
    });
    that.getDetails();
  },
  scroll: function (e) {
    //  该方法绑定了页面滚动时的事件，这里记录了当前的position.y的值,为了请求数据之后把页面定位到这里来。
    this.setData({
      scrollTop: e.detail.scrollTop
    });
  },
  goDetail: function (e) {
    var no = e.currentTarget.dataset.orderno;
    wx.navigateTo({
      url: '/pages/index/orderlist1/orderDetail1/orderDetail1?orderNo=' + no
    })
  },
  getDetails: function () {
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
    params.dept_id = that.data.dept_id;
    params.m_id = that.data.m_id;
    params.p_id = that.data.p_id;
    params.start = that.data.start;
    params.end = that.data.end;
    params.pageNumber = page;
    params.phone = adminObj.phone;
    params.password = app.globalData.password;
    params.sessionId = adminObj.sessionId;

    wx.request({
      url: app.globalData.requestUrl + "weixinMerchant/manageerStatisticForManageerOrPrincipal",
      data: params,
      success: function (res) {
        that.setData({
          hidden: true
        })
        if (res.data.code == '0') {
          var reqList = res.data.pageResults.list;
          if (reqList != null && reqList.length > 0) {
            for (var i in reqList) {//毛利计算保留2位小数
              var ord = reqList[i];
              ord.profit = (ord.predict_receive - ord.predict_give - ord.predict_pay).toFixed(2);
            }
            var listNew = that.data.list.concat(reqList);
            that.setData({
              list: listNew,
              page: page + 1,
              hasMore: true
            })
          } else {
            that.setData({
              hasMore: false
            })
            //one为点击切换订单状态,more为上拉加载
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