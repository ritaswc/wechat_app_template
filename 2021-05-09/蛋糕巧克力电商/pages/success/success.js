var base = getApp();
Page({
  data: {
    oid: "1703101349147978",
  },
  onLoad: function (options) {
    // 页面初始化 options为页面跳转所带来的参数
    var _this = this;
    _this.setData({
      "oid": options.oid?options.oid:_this.data.oid
    });

  },
  onReady: function () {
    // 页面渲染完成
  },
  onShow: function () {
    // 页面显示
  },
  onHide: function () {
    // 页面隐藏
  },
  onUnload: function () {
    // 页面关闭
  },

  go: function () {
    var _this = this;
    wx.navigateTo({
      url: "../user/myorderdetals/myorderdetals?oid=" + _this.data.oid
    })

  }

})