// pages/shenghuo/GoodLife/LifeSearchList/LifeSearchList.js

const config = require('../../../../config')
var util = require('../../../../utils/util.js')

var longt = ""
var lati = ""
var CategoryId = ""
var CtiyName = ""
var pageNo = 0;

Page({

  /**
   * 页面的初始数据
   */
  data: {
    LifeSearchList: []
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    //var url = 'LifeSearchList/LifeSearchList?CategoryId=' + CategoryId + '&longt=' + longt + '&lati=' + lati + '&localCtiyName=' + this.data.localCtiyName;
    CategoryId = options.CategoryId;
    lati = options.lati;
    longt = options.longt;
    CtiyName = options.localCtiyName;

    this.loadNewData()

  },
  /** 下拉刷新 */
  loadNewData: function (e) {
    pageNo = 1;
    this.requestLifeSearchList();

  },
  /** 上拉加载 */
  loadNewData_NextPage: function (e) {
    pageNo += 1;
    this.requestLifeSearchList();
  },


  requestLifeSearchList: function (e) {
    var that = this;
    let url = config.LifeSearchListUrl

    var that = this

    wx.showLoading({ title: '加载中...', })

    var distanceStr = null

    if (that.data.quanchengSelectedName === "1千米") {
      distanceStr = "1000"
    } else if (that.data.quanchengSelectedName === "3千米") {
      distanceStr = "3000"
    }
    else if (that.data.quanchengSelectedName === "5千米") {
      distanceStr = "5000"
    } else if (that.data.quanchengSelectedName === "10千米") {
      distanceStr = "10000"
    } else if (that.data.quanchengSelectedName === "全城") {
      distanceStr = null
    }

    var para = {
      "pageSize": 20,
      "pageNum": pageNo,
      "sortType": ("离我最近" === that.data.priceSelectedName ? 0 : 1), //排序方式 0- 距离排序 1-面额最高排序
      "position": {
        "distance": distanceStr,
        "latitude": lati,
        "longitude": longt
      },
      "fcId": CategoryId,
      "city": CtiyName,
      "country": null
    }

    wx.showLoading({ title: '加载中...' })

    util.RequestManager(url, para, function (res, fail) {

      wx.hideLoading()

      var tempAr = [];

      for (var i = 0; i < res.data.dataList.length; i++) {
        var model = res.data.dataList[i];
        model["starAr"] = util.convertToStarsArray(model["star"])
        model["distance"] = util.convertToDistance(model["distance"])
        tempAr.push(model)
      }

      if (pageNo == 1) {
        //下拉刷新
        that.setData({ LifeSearchList: tempAr })
      } else {
        //上拉加载
        that.setData({ LifeSearchList: that.data.LifeSearchList.concat(tempAr) })
      }
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
    this.loadNewData_NextPage();
  },

  /**
   * 用户点击右上角分享
   */
  onShareAppMessage: function () {

  }
})