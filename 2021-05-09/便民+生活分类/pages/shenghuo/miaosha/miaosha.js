// pages/shenghuo/miaosha/miaosha.js
const config = require('../../../config')
var pageNo = 0;
Page({

  /**
   * 页面的初始数据
   */
  data: {
    miaoshaList:[]
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
      url: config.miaoshaUrl,
      data: {
        "pageNum": pageNo,
        "limit": 20,
        "sessionType": 2,
        "typeId": 4
      },
      method: 'POST', // OPTIONS, GET, HEAD, POST, PUT, DELETE, TRACE, CONNECT
      // header: {}, // 设置请求的 header
      success: function (res) {
        // success

        that.setData({ miaoshaList: res.data.data.categoryObj })
        console.log(that.data.miaoshaList)
        
      },
      fail: function (res) {
        // fail
      },
      complete: function (res) {
        // complete
      }
    })
  },
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    this.loadNewData()

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
  
  },

  /**
   * 页面上拉触底事件的处理函数
   */
  onReachBottom: function () {
  
  },

  /**
   * 页面相关事件处理函数--监听用户下拉动作
   */
  onPullDownRefresh: function () {
  
  }
})