// pages/search/search.js

const requestUrl = require('../../config').requestUrl

var getTagData = function (that) {
  wx.request({
    url: requestUrl + 'wxTagListGet.ashx',
    success: function (res) {
      that.setData({
        tagList: res.data.ChinaValue
      })
    }
  })
}

var search = function (that) {
  if (that.data.key.length > 0) {
    wx.navigateTo({
      url: '../result/result?KeyWord=' + that.data.key
    })
  }
  else {
    wx.showToast({
      title: '输入关键字',
      image: "../../images/icon-no.png",
      mask: true,
      duration: 1000
    })
  }
}

Page({
  data: {
    key: ''
  },

  //事件处理函数
  formSearch: function () {
    search(this)
  },

  //点击标签
  bindTagTap: function (e) {
    wx.navigateTo({
      url: '../result/result?KeyWord=' + e.currentTarget.dataset.id
    })
  },

  //长按封面图 重新加载
  bindRefresh: function () {
    getTagData(this)
  },

  bindKeyInput: function (e) {
    this.setData({
      key: e.detail.value
    })
  },

  bindInputSearch: function () {
    search(this)
  },

  onLoad: function () {
    var that = this

    //调用应用实例的方法获取全局数据
    var app = getApp()
    app.getUserInfo(function (userInfo) {
      //更新数据
      that.setData({
        userInfo: userInfo
      })
    })
  },

  onShow: function () {
    var that = this

    that.setData({
      key: ''
    })
    getTagData(that)
  }
})