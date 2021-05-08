// pages/bianmin/bianmin.js

const config = require('../../config')
var pageNo = new Array();
var dic = new Array();

Page({
  data: {
    currentTabIndex: 1,
    list: [],
    newsTagList: []
  },
  scroll: function (e) {

    // console.log(e)
  },
  clickOrderTab: function (e) {
    //data = {};

    var dataset = e.target.dataset

    this.setData({ currentTabIndex: dataset.index })

    // data['pages'] = 1;
    // data['orderLists'] = [];
    // data['noMore'] = false;
    // if (dataset.goodsType) {
    //   data.currentGoodsType = dataset.goodsType;
    // }

    // this.setData(data);
    // this.getOrderList({ tabIndex: index });
    var index = parseInt(dataset.index)
    if (this.data.list[index]) {
      //有数据
      //nothing
    } else {
      //没有数据
      this.loadNewData(this.data.currentTabIndex);
    }


  },
  /** 跳转要闻详情页面 */
  tapHotNewsCell: function (event) {
    wx.navigateTo({
      url: '../index/HotNewsDetail/HotNewsDetail'
    })
  },
  onLoad: function (options) {
    // 页面初始化 options为页面跳转所带来的参数
    console.log('onLoad')

    //this.setData({ newsTagList : ["本地新闻","工作动态","信息公开","互动交流","招标公告"]})
    this.requestNewsTagData();

    this.loadNewData(this.data.currentTabIndex);

  },
  onReady: function () {
    // 页面渲染完成
  },
  onShow: function () {
    // 页面显示
    console.log("页面显示")

    // wx.showToast({
    //   title: '成功123',
    //   icon: 'success',
    //   duration: 1500
    // })
  },

  /** 下拉刷新 */
  loadNewData: function (tabIndex) {
    
    pageNo[tabIndex] = 1;
    this.requestData(tabIndex)

  },

  /** 上拉加载 */
  loadNewData_NextPage: function (tabIndex) {
    
    pageNo[tabIndex] += 1;
    this.requestData(tabIndex);
  },

  requestData: function (tabIndex) {
    var that = this

    wx.request({
      url: config.GET_HOT_NEWS + "?accessToken=",
      data: {
        "system": "02",
        "tagId": tabIndex,
        "accessToken": "",
        "scopeAddressCode": "5304",
        "key": "",
        "systemVersion": "10.1.1",
        "imei": "C75C7019-29FA-4F2B-8311-BAA6F29D1845",
        "currentVersion": "3.1.6",
        "sig": "",
        "pageNo": pageNo[tabIndex].toString(),
        "model": "iPhone 5s (A1457\/A1518\/A1528\/A1530)",
        "pageSize": "20"
      },
      method: 'POST', // OPTIONS, GET, HEAD, POST, PUT, DELETE, TRACE, CONNECT
      header: { 'content-type': 'application/json' }, // 设置请求的 header
      success: function (res) {
        // success
        if (pageNo[tabIndex] == 1) {
          dic[tabIndex] = res.data.data.list;
          that.setData({ list: dic })

        } else {
          dic[tabIndex] = dic[tabIndex].concat(res.data.data.list);
          that.setData({ list: dic })

        }

        console.log(that.data.list)

      },
      fail: function (res) {
        // fail
        pageNo[tabIndex] -= 1;
      },
      complete: function (res) {
        // complete
        wx.stopPullDownRefresh()
      }
    })

  },
  requestNewsTagData: function () {
    var that = this
    var tempNewsTagList = new Array();
    wx.request({
      url: config.newsTagUrl + "?accessToken=",
      data: {
        "system": "02",
        "imei": "A902EA47-B1B2-452A-96FB-4C7BCCBB149C",
        "currentVersion": "3.1.6",
        "model": "iPhone 6s Plus (A1699)",
        "accessToken": "",
        "systemVersion": "10.3.2",
        "sig": ""
      },
      method: 'POST', // OPTIONS, GET, HEAD, POST, PUT, DELETE, TRACE, CONNECT
      header: { 'content-type': 'application/json' }, // 设置请求的 header
      success: function (res) {
        // success
        tempNewsTagList = res.data.data.showTagList;
        tempNewsTagList = tempNewsTagList.concat(res.data.data.notShowTagList);

        that.setData({ newsTagList: tempNewsTagList })
        console.log(that.data.newsTagList)

        //初始化每个页面的pageNO
        for (var i = 0; i < tempNewsTagList.count; i++ ){
          var model = tempNewsTagList[i];
          
          pageNo[model[id]] = 1;

        }

      },
      fail: function (res) {
        // fail
      },
      complete: function (res) {
        // complete

      }
    })


  },

  onPullDownRefresh: function () {
    // 页面相关事件处理函数--监听用户下拉动作
    this.loadNewData(this.data.currentTabIndex);

  },
  onReachBottom: function () {
    // 页面上拉触底事件的处理函数
    console.log("onReachBottom")

    this.loadNewData_NextPage(this.data.currentTabIndex)
  }

})