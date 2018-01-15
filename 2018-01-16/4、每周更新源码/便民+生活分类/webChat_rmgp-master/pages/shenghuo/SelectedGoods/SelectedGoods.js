// pages/shenghuo/SelectedGoods/SelectedGoods.js

const config = require('../../../config')
var util = require('../../../utils/util.js')

Page({

  /**
   * 页面的初始数据
   */
  data: {
    prdFirstCategoryList: [], /** 分类种类数据 */
    advertCaroucelsAr: [], /** 轮播数据 */
    prdSearchList: [], /** 好商品搜索列表 */
    currentTabIndex: 0,
  },
  clickOrderTab: function (e) {
    //data = {};
    var index = parseInt(e.target.dataset.index)

    this.setData({ currentTabIndex: index })

    // /** 选中的分类名字 */
    // selectedCategoryName = this.data.couponCategoryList[index]["CategoryName"];
    // /** 选中的具体类型名字 */
    // selectedfcName = this.data.couponCategoryList[index]["cateName"];


    // if (this.data.couponSearchList[index]) {
    //   //有数据
    //   //nothing
    // } else {
    //   //没有数据
    //   /** 网络请求: 按条件搜索 */
    //   this.loadNewData(selectedCategoryName, selectedfcName, this.data.localCtiyName)
    // }

  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    this.requestprdFirstCategoryList()
    this.requestAdvertCaroucels()
    this.requestPrdSearchList()
  },
  /**获取好商品所有一级分类*/
  requestprdFirstCategoryList: function () {
    var that = this;
    let url = config.PrdFirstCategoryUrl

    var para = {}

    wx.showLoading({ title: '加载中...' })

    util.RequestManager(url, para, function (res, fail) {

      wx.hideLoading()

      var tempAr = res.data[0].facetValues;
      var resultAr = new Array();
      var index = 0;
      for (var i = 0; i < tempAr.length; i++ ){
        var model = tempAr[i];
        var dic = {};
        dic["id"] = index;
        dic["count"] = model["count"];
        dic["cateName"] = model["name"];
        
        resultAr.push(dic)

        index++;
      }

      that.setData({ prdFirstCategoryList: resultAr })

    })

  },
  /**获取广告轮播 */
  requestAdvertCaroucels: function (e) {
    var that = this;
    let url = config.AdvertCaroucelsUrl

    var para = {
      "category": 4
    }

    wx.showLoading({ title: '加载中...' })

    util.RequestManager(url, para, function (res, fail) {

      wx.hideLoading()
      that.setData({ advertCaroucelsAr: res.data })

    })
  },
  /**获取好商品搜索数据 */
  requestPrdSearchList: function () {
    var that = this;
    let url = config.PrdSearchListUrl

    var para = {
      "pageNum": 1,
      "pageSize": 20,
      "doorCateId": ["13285", "13286", "13619"],
      "fcName": "生活美食"
    }

    wx.showLoading({ title: '加载中...' })

    util.RequestManager(url, para, function (res, fail) {

      wx.hideLoading()
      that.setData({ prdSearchList: res.data.dataList })

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