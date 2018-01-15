var app = getApp();
Page({
  data: {
    adminDept: '',
    start: '',
    end: '',
    id: '',
    name: '',
    list: [],
    total: '',
    hidden: false
  },
  onLoad: function (option) {
    console.log(option);
    var that = this;
    that.setData({
      adminDept: app.globalData.adminObj.dept_id,
      orderType: option.orderType,
      start: option.start,
      end: option.end,
      id: option.id,
      name: option.name
    })
    that.manageerStatisticForDeptOrManageer(that);
  },
  goDetail: function (e) {
    var that = this, start = that.data.start, end = that.data.end;
    var dept_id = e.currentTarget.dataset.deptid, dept = e.currentTarget.dataset.dept;
    var m_id = e.currentTarget.dataset.mid, manageer = e.currentTarget.dataset.manageer;
    var p_id = e.currentTarget.dataset.pid, principal = e.currentTarget.dataset.principal;
    wx.navigateTo({
      url: '/pages/index/channelOrder_statistics/department/personal/personal?dept_id=' + dept_id + '&m_id=' + m_id + '&p_id=' + p_id + '&start=' + start + '&end=' + end + '&dept=' + dept + '&manageer=' + manageer + '&principal=' + principal
    })
  },
  manageerStatisticForDeptOrManageer: function (that) {
    var that = this, adminObj = app.globalData.adminObj;
    wx.request({
      url: app.globalData.requestUrl + "weixinMerchant/manageerStatisticForDeptOrManageer",
      data: {
        order_type: that.data.orderType,
        id: that.data.id,
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
          var map = res.data.mapResults, manageer = map.stats, total = map.total;
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