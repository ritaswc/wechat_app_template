// pages/index/index.js
//获取应用实例
var app = getApp()
Page({
  data: {
    imageCtx: app.globalData.imageCtx,
    merchant: '',
    search: '',
    hasMessage: false
  },
  onShareAppMessage: function () {//分享
    return {
      path: '/pages/index/index'
    }
  },
  onShow: function () {
    var that = this, adminObj = app.globalData.adminObj;
    that.setData({
      search: ''
    })
    if (adminObj) {
      that.setData({
        merchant: adminObj.merchant_name
      })
      that.hasMessage();
    } else {
      that.setData({
        merchant: ''
      })
    }
  },
  searchInput: function (e) {
    this.setData({
      search: e.detail.value
    })
  },
  login: function () {
    wx.navigateTo({
      url: '/pages/login/login'
    })
  },
  //退出到登录页面
  loginOut: function () {
    var that = this;
    wx.showModal({
      content: "退出登录?",
      success: function (res) {
        if (res.confirm) {
          that.setData({
            merchant: ''
          })
          wx.setStorageSync('dealerAdminObj', '');
          app.globalData.adminObj = '';
          wx.navigateTo({
            url: '/pages/login/login'
          })
        }
      }
    })
  },
  bindSearchTap: function (e) {
    var that = this, merchant = that.data.merchant;
    if (!merchant) {
      that.noLogin(that);
      return;
    }
    var search = that.data.search, reg = /^\s*|\s*$/g;
    search = search.replace(reg, '');
    if (search) {
      wx.navigateTo({
        url: '/pages/index/search/search?search=' + that.data.search
      })
    } else {
      app.warning('请输入订单名称或单号');
      if (e != undefined) {
        console.log(e)
      }
    }
  },
  hasMessage: function () {
    var that = this, merchant = that.data.merchant, adminObj = app.globalData.adminObj;
    if (!merchant) {
      that.noLogin(that);
      return;
    }
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
        }
      }
    })
  },
  goMessage: function () {
    var that = this, merchant = that.data.merchant;
    if (!merchant) {
      that.noLogin(that);
      return;
    }
    wx.navigateTo({
      url: '/pages/index/message/message'
    })
  },
  goPrintOrder: function () {
    var that = this, merchant = that.data.merchant;
    if (!merchant) {
      that.noLogin(that);
      return;
    }
    wx.navigateTo({
      url: '/pages/index/orderlist1/orderlist1'
    })
  },
  goDraftOrder: function () {
    var that = this, merchant = that.data.merchant;
    if (!merchant) {
      that.noLogin(that);
      return;
    }
    wx.navigateTo({
      url: '/pages/index/drafts/drafts'
    })
  },
  goInOutOrder: function () {
    var that = this, merchant = that.data.merchant;
    if (!merchant) {
      that.noLogin(that);
      return;
    }
    wx.navigateTo({
      url: '/pages/index/orderlist2/orderlist2'
    })
  },
  goNormal: function () {
    var that = this, merchant = that.data.merchant;
    if (!merchant) {
      that.noLogin(that);
      return;
    }
    wx.navigateTo({
      url: '/pages/index/basicInfo_statistics/basicInfo_statistics'
    })
  },
  goManageer: function () {
    var that = this, merchant = that.data.merchant;
    if (!merchant) {
      that.noLogin(that);
      return;
    }
    wx.navigateTo({
      url: '/pages/index/channelOrder_statistics/channelOrder_statistics'
    })
  },
  goOrderSell: function () {
    var that = this, merchant = that.data.merchant;
    if (!merchant) {
      that.noLogin(that);
      return;
    }
    wx.navigateTo({
      url: '/pages/index/orderSell_statistics/orderSell_statistics'
    })
  },
  goCustomer: function () {
    var that = this, merchant = that.data.merchant;
    if (!merchant) {
      that.noLogin(that);
      return;
    }
    wx.navigateTo({
      url: '/pages/index/clientMes_statistics/clientMes_statistics'
    })
  },
  goSource: function () {
    var that = this, merchant = that.data.merchant;
    if (!merchant) {
      that.noLogin(that);
      return;
    }
    wx.navigateTo({
      url: '/pages/index/orderSource_statistics/orderSource_statistics'
    })
  },
  goStyleSell: function () {
    var that = this, merchant = that.data.merchant;
    if (!merchant) {
      that.noLogin(that);
      return;
    }
    wx.navigateTo({
      url: '/pages/index/styleSell_statistics/styleSell_statistics'
    })
  },
  goDesign: function () {
    var that = this, merchant = that.data.merchant;
    if (!merchant) {
      that.noLogin(that);
      return;
    }
    wx.navigateTo({
      url: '/pages/index/designCost_statistics/designCost_statistics'
    })
  },
  goStore: function () {
    var that = this, merchant = that.data.merchant;
    if (!merchant) {
      that.noLogin(that);
      return;
    }
    wx.navigateTo({
      url: '/pages/index/repertoryCentre/repertoryCentre'
    })
  },
  noLogin: function (that) {
    wx.showModal({
      content: '请先登录',
      success: function (res) {
        if (res.confirm) {
          that.login()
        }
      }
    })
  },
})