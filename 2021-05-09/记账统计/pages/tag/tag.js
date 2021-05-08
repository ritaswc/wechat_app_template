// pages/tag/tag.js

const requestUrl = require('../../config').requestUrl

Page({
  data: {},

  //点击标签
  bindTagTap: function (e) {
    wx.setStorage({
      key: "selectedTag",
      data: e.currentTarget.dataset.id
    })

    wx.navigateBack({
      delta: 1
    })
  },

  onLoad: function (options) {
    var that = this
    wx.request({
      url: requestUrl + 'wxTagListGet.ashx',
      success: function (res) {
        that.setData({
          tagList: res.data.ChinaValue
        })
      }
    })
  }
})