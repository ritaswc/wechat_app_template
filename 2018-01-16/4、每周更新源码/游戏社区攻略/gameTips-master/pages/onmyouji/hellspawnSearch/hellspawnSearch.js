
/**
 * pages/onmyouji/hellspawnSearch/hellspawnSearch.js
 * 
 * @description 式神搜索页
 * @version 1.0.0
 * 
 */

var http = require("../../../utils/http.js");
var app = getApp();

Page({

  data: {
    popularList: [],
    inputShowed: false,
    inputVal: "",
    hellspawnList: []
  },

  onShow: function () {
    this.getPopular();
  },

  // 获取 热门搜索
  getPopular: function () {
    //console.log(http)
    var url = http.generateUrl('api/v1/populars');
    var _this = this;
    wx.request({
      url: url,
      method: 'GET', // OPTIONS, GET, HEAD, POST, PUT, DELETE, TRACE, CONNECT
      // header: {}, // 设置请求的 header
      success: function (res) {
        console.log(res)
        // success
        if (res.data.status == 1) {
          _this.setData({
            popularList: res.data.body.popular_list
          });
        }
      },
      fail: function (error) {
        console.error(error)
      }
    })
  },

  // 显示搜索input
  showInput: function () {
    this.setData({
      inputShowed: true
    });
  },

  // 隐藏搜索input
  hideInput: function () {
    this.setData({
      inputVal: "",
      inputShowed: false
    });
  },

  // 清空搜索input
  clearInput: function () {
    this.setData({
      inputVal: ""
    });
  },

  // 搜索input状态改变时
  inputTyping: function (e) {
    var _this = this;
    this.setData({
      inputVal: e.detail.value
    });
    if (e.detail.value) {
      var url = http.generateUrl('api/v1/search/' + e.detail.value);
      wx.request({
        url: url,
        method: 'GET',
        success: function (res) {
          if (res.data.status == 1) {
            _this.setData({
              hellspawnList: res.data.body.hellspawn_list
            })
          }
        }
      })
    }
  }

})
