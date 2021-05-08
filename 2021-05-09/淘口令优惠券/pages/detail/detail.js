var app = getApp()
Page({
  data: {
    couponInfo: {},
    picWidth: wx.getSystemInfoSync().windowWidth,
    platformTypeUrl: "../../images/taobao.png",
    loadingBtn: false,
    showStatus: false,
    taoKouLing: "",
    maxLength: 0
  },
  onShow: function () {
    wx.setStorageSync('isDetailBack', true)
  },
  onLoad: function (options) {
    this.setData({
      couponInfo: wx.getStorageSync('couponInfo')
    })
    if (this.data.couponInfo.PlatformType == "天猫")
      this.setData({
        platformTypeUrl: "../../images/tmall.png"
      })
  },
  hideView: function () {
    this.setData({
      showStatus: false
    })
  },
  getCoupon: function (options) {
    var that = this
    that.setData({
      loadingBtn: true
    })
    wx.request({
      url: "https://taoquan.cillbiz.com/GetCouponDetail.ashx",
      data: {
        "Acount": {
          "UserName": app.globalData.Acount.UserName,
          "PassWord": app.globalData.Acount.PassWord
        },
        "Condition": {
          "ItemID": that.data.couponInfo.ItemID,
          "CouponID": that.data.couponInfo.CouponID
        }
      },
      method: "POST",
      success: function (resRequest) {
        if (resRequest.data.Result == "请求成功") {
          that.setData({
            taoKouLing: resRequest.data.QuanDetail.TaoKouLing,
            loadingBtn: false,
            showStatus: true,
            maxLength: resRequest.data.QuanDetail.TaoKouLing.length
          })
        }
      }
    })
  }
})