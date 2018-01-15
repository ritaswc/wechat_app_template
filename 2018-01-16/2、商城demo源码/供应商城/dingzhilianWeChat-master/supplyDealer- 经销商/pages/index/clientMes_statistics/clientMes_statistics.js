var app = getApp();
Page({
  data: {
    imageCtx: app.globalData.imageCtx,
    hidden: false,
    emptyShow: false,
    adminDept: '',
    depts: [],
    dept: '',
    deptId: '',
    manageer: '',
    manageerId: '',
    manageers: [],
    customer: [],
    deptIndex: 0,
    manageerIndex: 0,
    total: 0
  },
  onLoad: function () {
    var that = this;
    that.setData({
      adminDept: app.globalData.adminObj.dept_id
    })
    that.deptManageer(that);
  },
  goCity: function (e) {
    var that = this, deptId = that.data.deptId, manageerId = that.data.manageerId;
    var province = e.currentTarget.dataset.province;
    wx.navigateTo({
      url: '/pages/index/clientMes_statistics/clientMes-city/clientMes-city?province=' + province + '&deptId=' + deptId + '&manageerId=' + manageerId
    })
  },
  deptManageer: function (that) {
    var adminObj = app.globalData.adminObj;
    wx.request({
      url: app.globalData.requestUrl + "weixinMerchant/getDeptAndManageer",
      data: {
        phone: adminObj.phone,
        password: app.globalData.password,
        sessionId: adminObj.sessionId
      },
      success: function (res) {
        that.setData({
          hidden: true
        })
        if (res.data.code == '0') {
          var depts = res.data.mapResults.depts, manageers = res.data.mapResults.manageers;
          depts.unshift({ 'name': '全部' });
          manageers.unshift({ 'name': '全部' });
          that.setData({
            depts: depts,
            manageers: manageers
          })
          that.customer(that);
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
  customer: function (that) {
    var that = this, params = {}, adminObj = app.globalData.adminObj;
    params.deptId = that.data.deptId;
    params.manageerId = that.data.manageerId;
    params.phone = adminObj.phone;
    params.password = app.globalData.password;
    params.sessionId = adminObj.sessionId;
    wx.request({
      url: app.globalData.requestUrl + "weixinMerchant/customerForProvince",
      data: params,
      success: function (res) {
        that.setData({
          hidden: true
        })
        if (res.data.code == '0') {
          var map = res.data.mapResults;
          var customer = map.stats, total = map.total;
          if (customer && customer.length > 0) {
            that.setData({
              customer: customer,
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
  bindPickerChange1: function (e) {
    console.log('picker发送选择改变，携带值为', e.detail.value)
    var that = this, depts = that.data.depts, index = e.detail.value;
    if (index != 0) {
      that.setData({
        hidden: false,
        deptIndex: index,
        deptId: depts[index].id,
        dept: depts[index].name
      })
    } else {
      that.setData({
        hidden: false,
        deptIndex: index,
        deptId: '',
        dept: depts[index].name
      })
    }
    that.customer(that);
  },
  bindPickerChange2: function (e) {
    var that = this, manageers = that.data.manageers, index = e.detail.value;
    if (index != 0) {
      that.setData({
        hidden: false,
        manageerIndex: index,
        manageerId: manageers[index].id,
        manageer: manageers[index].name
      })
    } else {
      that.setData({
        hidden: false,
        manageerIndex: index,
        manageerId: '',
        manageer: manageers[index].name
      })
    }
    that.customer(that);
  }
})