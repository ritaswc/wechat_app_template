var app = getApp();
Page({
  data: {
    hidden: false,
    province: '',
    city10: [],
    deptId: '',
    manageerId: '',
    total: 0
  },
  onLoad: function (option) {
    console.log(option);
    var that = this;
    var province = option.province,deptId = option.deptId, manageerId = option.manageerId;
    that.setData({
      province:province,
      deptId: deptId,
      manageerId: manageerId
    })
    that.city(that);
  },
  city: function (that) {
    var that = this, params = {}, adminObj = app.globalData.adminObj;
    params.province = that.data.province;
    params.deptId = that.data.deptId;
    params.manageerId = that.data.manageerId;
    params.phone = adminObj.phone;
    params.password = app.globalData.password;
    params.sessionId = adminObj.sessionId;
    wx.request({
      url: app.globalData.requestUrl + "weixinMerchant/customerForProvinceCity",
      data: params,
      success: function (res) {
        that.setData({
          hidden: true
        })
        if (res.data.code == '0') {
          var map = res.data.mapResults, city10 = [];
          var city = map.stats, total = map.total;
          for (var c in city) {
            city10.push(city[c]);
            if (c == 9) {//只获取前10城市
              break;
            }
          }
          that.setData({
            city10: city10,
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