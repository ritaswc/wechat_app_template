// pages/shenghuo/AllServiceclassify/ServiceDetailInfo/ServiceDetailInfo.js

const config = require('../../../../config')
var util = require('../../../../utils/util.js')
var WxParse = require('../../../../wxParse/wxParse.js');

Page({

  /**
   * 页面的初始数据
   */
  data: {

    detailDic: {}
  },
  wxParseTagATap: function (e) {

    var str = e.currentTarget.dataset.src;
    if (str instanceof Array) { //判断是否为数组类型
      if (str.length > 1) {
        str = str[0]
      }
    }
    var strAr = new Array(); //定义一数组 
    strAr = str.split(":"); //字符分割 

    console.log(strAr)

    if (strAr[0] == "tel") {

      let phoneNum = strAr[1]
      //拨打电话
      wx.makePhoneCall({
        phoneNumber: phoneNum,
      })
    }


  },
  /** 去掉转义字符 */
  excludeSpecial: function (s) {

    s = s.replace('"', '').replace(/[\\]/g, '');

    return s;
  },
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    var id = options.id;

    //网络请求
    var that = this

    console.log(options.index)

    let url = config.ServiceDetailInfoUrL
    let para = { "id": id, }

    util.RequestManager(url, para, function (res, fail) {
      console.log(res)
      that.setData({ detailDic: res.data })

      var htmlStr = that.excludeSpecial(res.data.serviceDescription)

      WxParse.wxParse('htmlContentStr', 'html', htmlStr, that, 5);
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