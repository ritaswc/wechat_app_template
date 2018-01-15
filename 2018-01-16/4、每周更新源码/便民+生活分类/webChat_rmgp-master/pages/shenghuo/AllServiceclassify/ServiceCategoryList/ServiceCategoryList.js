// pages/shenghuo/AllServiceclassify/ServiceCategoryList/ServiceCategoryList.js

const config = require('../../../../config')
var util = require('../../../../utils/util.js')

Page({

  /**
   * 页面的初始数据
   */
  data: {
    list: []
  },
  /** 点击cell拨打电话 */
  tapServiceCategoryListCellCall: function(e) {
    var phoneNum = e.currentTarget.dataset.phoneNum;
    wx.makePhoneCall({
      phoneNumber: phoneNum,
    })

  },
  /**点击cell*/
  tapServiceCategoryListCell: function(e){
    
    var that = this;
    var id = e.currentTarget.dataset.id;

    let url = '../ServiceDetailInfo/ServiceDetailInfo?id=' + id;
    wx.navigateTo({
      url: url,
    })


  },
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {

    var CategoryId = options.CategoryId;

    //网络请求
    var that = this

    console.log(options.index)

    let url = config.ServiceSearchListUrl
    let para = {
      "pageNum": 1,
      "pageSize": 20,
      "position": {},
      "serviceSecondCategoryId": CategoryId,
      "serviceAreaName": "云南省 玉溪市"
    }

    util.RequestManager(url, para, function (res, fail) {
      console.log(res)
      that.setData({ list: res.data.dataList })
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