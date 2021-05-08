const app = getApp();
var utils = require('../../../utils/utils.js')
var wxParse = require('../../../wxParse/wxParse.js')
// 定义一个全局变量保存从接口获取到的数据，以免重复请求接口
var resut;
Page({

  /**
   * 页面的初始数据
   */
  data: {
    article: '',
    articleList: [],
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    wx.showToast({ title: '玩命加载中', icon: 'loading', duration: 10000 });
    var that = this;
    //获取所有文章的列表
    var url = 'https://recod.cn:8081/api/v2.0/topic/?kind=娱乐&pageNum=1';
    var that = this
    utils.myRequest({
      url: url,
      methods: 'GET',
      success: function (result) {
        wx.hideToast()
        var valueImgList = (result.data['pagesDict']['value'])
        that.setData({
          showitem: true,
          articleList: valueImgList
        })
      },
      fail: function () {
        that.setData({
          showitem: false
        })
      }
    })



  },
  handlerGobackClick(delta) {
    utils.handlerClick.GobackClick(delta)
  },
  handlerGohomeClick() {
    utils.handlerClick.GohomeClick()
  },
  //渲染函数
  renderHtml: function () {
    var that = this
    var article = that.data.article

    wxParse.wxParse('article', 'html', article, that, 5);
  },
  //去网页细节
  toPagedetail: function (e) {
    //wxml通过data-传参数

    var pageUrl = e.currentTarget.dataset['pageurl']
    // var pagesource= JSON.stringify(e.currentTarget.dataset['pagesourcewithtitle']);
    var pagesource = encodeURIComponent(e.currentTarget.dataset['pagesourcewithtitle']);
    //普通字符串

    //这里直接这样打印会报错的，为什么？
    // console.log(pagesourceWithTitle);
    wx.navigateTo({
      url: '/pages/index/detail/detail?pagesource=' + pagesource,
    })
  },
})