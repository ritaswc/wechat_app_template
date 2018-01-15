//index.js
//获取应用实例
var app = getApp()
var request = require('../../utils/https.js')
var uri_home = 'floor/api/indexListAll'
Page({
  data: {
    motto: '请输入您要搜索的商品',
    list: []
  },

  click: function () {
    wx.navigateTo({
      url: '../goodsearch/goodsearch',
    })
  },
  //跳转到goodsdetail
  itemclick: function (e) {
    var specId = e.currentTarget.dataset.specid;
    wx.navigateTo({
      url: '../goodsDetail/goodsDetail?specId=' + specId,

    })
  },
  onLoad: function () {
    //调登陆接口
    wx.login({
      success: function (res) {
        if (res.code) {
          //存储 code
          var codeinfo = {
            code: res.code,
          };
          wx.setStorageSync('codeinfo', codeinfo)
        }
      },
      fail: function () {
        console.log("授权失败");
      },
      complete: function () {

      }
    })
    var that = this
    //获取为你推荐数据
    request.req(uri_home, { apKey: 'advh5' },
      (err, res) => {
        console.log(res.data)
        if (res.data.result == 1) {
          that.setData({
            list: res.data.data[0].relGoodsRecommedlist
          })
        }
      })
  },
})
