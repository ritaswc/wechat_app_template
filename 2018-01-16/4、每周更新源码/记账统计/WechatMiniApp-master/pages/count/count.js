// pages/count/count.js
const requestUrl = require('../../config').requestUrl
var requestYear = new Date().getFullYear()
var requestMonth = (("0" + (new Date().getMonth() + 1)).slice(-2))

var getCountList = function (that) {
  wx.request({
    url: requestUrl + 'wxCount.ashx',
    data: {
      Type: 'ALL',
      Year: requestYear,
      Month: requestMonth
    },
    success: function (res) {
      that.setData({
        listAll: res.data.ChinaValue
      })
    }
  })

  wx.request({
    url: requestUrl + 'wxCount.ashx',
    data: {
      Type: 'CAT',
      Year: requestYear,
      Month: requestMonth
    },
    success: function (res) {
      that.setData({
        listCat: res.data.ChinaValue
      })
    }
  })

  wx.request({
    url: requestUrl + 'wxCount.ashx',
    data: {
      Type: 'INC',
      Year: requestYear,
      Month: requestMonth
    },
    success: function (res) {
      that.setData({
        listInc: res.data.ChinaValue
      })
    }
  })
}

Page({
  data: {
    listAll: [],
    listCat: [],
    listInc: [],

    optionColorId: 3
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

    getCountList(that)
  },

  //分类查看
  bindOptionTap: function (e) {
    var that = this
    that.setData({
      optionColorId: e.target.id
    })

    switch (e.target.id) {
      case '1':
        requestYear = '0'
        requestMonth = '00'
        getCountList(that)
        break;

      case '2':
        requestYear = '2017'
        requestMonth = '00'
        getCountList(that)
        break;

      case '3':
        requestYear = new Date().getFullYear()
        requestMonth = (("0" + (new Date().getMonth() + 1)).slice(-2))
        getCountList(that)
        break;
    }
  },

  bindAll: function (e) {
    var that = this

    var param = e.currentTarget.dataset.id.split("-")

    switch (param.length) {
      case 1:
        requestYear = param[0]
        requestMonth = '00'
        getCountList(that)

        that.setData({
          optionColorId: 2
        })
        break;

      case 2:
        requestYear = param[0]
        requestMonth = param[1]
        getCountList(that)

        that.setData({
          optionColorId: 3
        })
        break;

      case 3:
        wx.navigateTo({
          url: '../result/result?DateTime=' + e.currentTarget.dataset.id
        })
        break;
    }
  },

  bindCat: function (e) {
    wx.navigateTo({
      url: '../result/result?Year=' + requestYear + '&Month=' + requestMonth + '&CID=' + e.currentTarget.dataset.id
    })
  }
})