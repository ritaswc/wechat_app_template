
var util = require("../../utils/util.js")
var crypt = require("../../utils/crypt.js")
var app = getApp();

Page({
  data: {
    info: {}
  },
  onLoad: function (options) {
    // 页面初始化 options为页面跳转所带来的参数
    // 获取用户信息
    this.init();
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


  /**
   * 获取用户关注论坛列表
   */
  init: function () {
    let that = this;
    app.getInit(function (result) {
      var tmpFile = result.obj.tmpFile;
      var minisns = result.obj._Minisns;
      var user = result.obj._LookUser;
      var verifyModel = util.primaryLoginArgs(user.unionid);
      that.setData({
        user: user,
        minisns: minisns,
        info: { articleCount: user.ArticleCount }
      })
    })
  },

  /**
   * 积分列表
   */
  integralLog: function () {
    wx.navigateTo({
      url: '/pages/score/score',
      complete: function () { console.log("跳转到积分列表") }
    })
  },
  /**
   * 我的发帖
   */
  myArticleList: function () {
    let that = this;
    wx.navigateTo({
      url: '/pages/mypost/mypost?uid=' + that.data.user.Id,
      complete: function () {
        console.log("跳转到我的发帖");
      }
    })
  }
})