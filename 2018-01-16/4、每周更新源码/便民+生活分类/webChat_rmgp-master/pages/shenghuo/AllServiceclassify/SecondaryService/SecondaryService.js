// pages/shenghuo/AllServiceclassify/SecondaryService/SecondaryService.js

const config = require('../../../../config')
var util = require('../../../../utils/util.js')

Page({

  /**
   * 页面的初始数据
   */
  data: {
    dic: []
  },

  //点击cell
  tapGridCell: function (e) {
    var CategoryId = e.currentTarget.dataset.categoryId;
    var url = '../ServiceCategoryList/ServiceCategoryList?CategoryId=' + CategoryId;

    wx.navigateTo({
      url: url,
    })
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {

    var that = this

    console.log(options.index)

    var index = options.index;

    let url = config.ServiceTypeListUrl
    let para = {}

    util.RequestManager(url, para, function (res, fail) {
      console.log(res)
      that.setData({ dic: res.data[index] })
    })
  },

  /**
   * 生命周期函数--监听页面初次渲染完成
   */
  onReady: function () {

  },

  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function () {

  },

  /**
   * 生命周期函数--监听页面隐藏
   */
  onHide: function () {

  },

  /**
   * 生命周期函数--监听页面卸载
   */
  onUnload: function () {

  },

  /**
   * 页面相关事件处理函数--监听用户下拉动作
   */
  onPullDownRefresh: function () {

  },

  /**
   * 页面上拉触底事件的处理函数
   */
  onReachBottom: function () {

  },

  /**
   * 用户点击右上角分享
   */
  onShareAppMessage: function () {

  }
})