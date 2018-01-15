// pages/index/index.js
//获取应用实例
var app = getApp()
Page({
  data: {
    imageCtx: app.globalData.imageCtx,
    search: '',
    hasMessage: false,
    listData:[
      {"code":"01","text":"text1","type":"type1"},
      {"code":"02","text":"text2","type":"type2"},
      {"code":"03","text":"text3","type":"type3"},
      {"code":"04","text":"text4","type":"type4"},
      {"code":"05","text":"text5","type":"type5"},
      {"code":"06","text":"text6","type":"type6"},
      {"code":"07","text":"text7","type":"type7"}
    ]
  },
  onShow: function () {
    this.hasMessage();
  },
  searchInput: function (e) {
    this.setData({
      search: e.detail.value
    })
  },
  //登录跳转首页
  bindSearchTap: function () {
    var that = this;
    var search = that.data.search, reg = /^\s*|\s*/g;
    search = search.replace(reg, '');
    if (search) {
      wx.navigateTo({
        url: '/pages/index/search/search?search=' + that.data.search
      })
    } else {
      app.warning('请输入订单单号或名称');
    }
  },
  hasMessage: function () {
    var that = this, adminObj = app.globalData.adminObj;
    wx.request({
      url: app.globalData.requestUrl + 'weixinMerchant/hasMessage',
      data: {
        phone: adminObj.phone,
        password: app.globalData.password,
        sessionId: adminObj.sessionId
      },
      success: function (res) {
        if (res.data.code == '0') {
          var num = res.data.result;
          if (num > 0) {
            that.setData({
              hasMessage: true
            })
          } else {
            that.setData({
              hasMessage: false
            })
          }
        } else {
          app.noLogin(res.data.msg);
        }
      },
      fail: function () {
        app.warning('服务器响应超时');
      }
    })
  }
})