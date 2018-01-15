//index.js
//获取应用实例
var app = getApp()
const config = require('../../config')
const iconList = require('../../data/local-data')
var util = require('../../utils/util.js')

var pageNo = 0;
Page({
  data: {
    carrouselsAr: [], /** 轮播 */
    list: [],  /** 要闻列表 */
  },

  /** 跳转（政务资讯、办事指南、办事大厅、办事攻略） */
  tapGridCell: function (event) {
    switch (event.currentTarget.dataset.iconId) {
      case 0:
        console.log("点击政务资讯")
        break
      case 1:
        console.log("点击办事指南")
        break
      case 2:
        console.log("点击办事大厅")
        break
      case 3:
        console.log("点击办事攻略")
        break
    }

     wx.navigateTo({
      url: 'temp/temp'
    })
  },

  /** 跳转要闻详情页面 */
  tapHotNewsCell: function (event) {
    wx.navigateTo({
      url: 'HotNewsDetail/HotNewsDetail'
    })
  },

  /** 首页轮播请求 */
  requestAppinitData: function(){
    var that = this;
    let url = config.AppinitData

    var para = {
      "system": "02",
      "currentVersion": "3.1.81",
      "imei": "C75C7019-29FA-4F2B-8311-BAA6F29D1845",
      "model": "iPhone 5s (A1457\/A1518\/A1528\/A1530)",
      "systemVersion": "10.3.2",
      "accessToken": "",
      "sig": "",
      "scopeAddressCode": ""
    }

    wx.showLoading({ title: '加载中...' })

    util.RequestManager(url, para, function (res, fail) {

      wx.hideLoading()

      if (res.code == app.globalData.res_success) {
        //成功
        that.setData({ carrouselsAr: res.data.carrousels})
      }
    })

  },

  /** 下拉刷新 */
  loadNewData: function () {
    pageNo = 1;
    this.requestData()

  },

  loadNewData_NextPage: function () {
    pageNo += 1;
    this.requestData();
  },

  requestData: function () {
    var that = this

    wx.request({
      url: config.GET_HOT_NEWS,
      data: {
        "system": "02",
        "tagId": "1",
        "accessToken": "",
        "scopeAddressCode": "",
        "key": "",
        "systemVersion": "10.3.1",
        "imei": "A902EA47-B1B2-452A-96FB-4C7BCCBB149C",
        "currentVersion": "3.1.6",
        "sig": "",
        "pageNo": pageNo,
        "model": "iPhone 6s Plus (A1699)",
        "pageSize": "20"
      },
      method: 'POST', // OPTIONS, GET, HEAD, POST, PUT, DELETE, TRACE, CONNECT
      header: { 'content-type': 'application/json' }, // 设置请求的 header
      success: function (res) {
        // success
        if (pageNo == 1) {
          that.setData({ list: res.data.data.list })
          
        }else {
          that.setData({ list: that.data.list.concat(res.data.data.list) })

        }

        console.log(that.data.list)
        
      },
      fail: function (res) {
        // fail
        pageNo--;
      },
      complete: function (res) {
        // complete
        wx.stopPullDownRefresh()
      }
    })

  },


  onLoad: function () {
    console.log('onLoad')
    /** 设置首页四图标 */
    console.log(iconList)
    this.setData(iconList)

    /** 请求首页轮播 */
    this.requestAppinitData();

    /** 请求要闻 */
    this.loadNewData();

    //调用应用实例的方法获取全局数据
    // app.getUserInfo(function(userInfo){
    //   //更新数据
    //   that.setData({
    //     userInfo:userInfo
    //   })
    // })
  },
  onSwiperTap: function (event) {
    // target 和currentTarget
    // target指的是当前点击的组件 和currentTarget 指的是事件捕获的组件
    // target这里指的是image，而currentTarget指的是swiper
    var postId = event.target.dataset.postid;
    wx.navigateTo({
      url: "post-detail/post-detail?id=" + postId
    })
  },
  onPullDownRefresh: function () {
    // 页面相关事件处理函数--监听用户下拉动作
    this.loadNewData();

  },
  onReachBottom: function () {
    // 页面上拉触底事件的处理函数
    console.log("onReachBottom")
    
    this.loadNewData_NextPage()
  },


})
