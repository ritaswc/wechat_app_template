var app = getApp()
var login = require('../../utils/uctoo-login.js');

Page({
  data: {
    open: false,
    display: "none",
    swiper: [],
    goods: [],
    test:'<img src="../../images/icons/iocn_home_01.png" />',
    iconslist:[
      {
        imgurl:'../../images/icons/iocn_home_01.png',
        name:'最新商品'
      },
      {
        imgurl:'../../images/icons/iocn_home_02.png',
        name:'友货推荐'
      },
      {
        imgurl:'../../images/icons/iocn_home_03.png',
        name:'送礼最佳'
      },
      {
        imgurl:'../../images/icons/iocn_home_04.png',
        name:'优惠活动'
      }
    ]
  },
  //详情页跳转
  lookdetail: function (e) {
    console.log(e)
    var lookid = e.currentTarget.dataset;
    console.log(e.currentTarget.dataset);
    wx.navigateTo({
      url: "../list/detail/detail?id=" + lookid.id
    })
  },
  //下拉刷新
  onPullDownRefresh: function() {
    console.log(0)
    this.onLoad()
  },
  //分享
  onShareAppMessage: function () {
    return {
      title: '微商城',
      path: '/pages/index/index'
    }
  },
  listdetail:function(e){
    console.log(e.currentTarget.dataset.title)
    wx.navigateTo({
      url: '/pages/list/listdetail/listdetail?title='+e.currentTarget.dataset.title,
      success: function(res){
        // success
      },
      fail: function() {
        // fail
      },
      complete: function() {
        // complete
      }
    })
  },
  onReady:function(){

  },
  onLoad: function (option) {
      login.login();
    var that=this
    console.log(0)
      //轮播数据
    wx.request({
      url: `${app.globalData.API_URL}/slides`,
      method: 'GET', // OPTIONS, GET, HEAD, POST, PUT, DELETE, TRACE, CONNECT
      // header: {}, // 设置请求的 header
      success: function (res) {
        console.log(res, '输出了什么');
        that.setData({
          swiper: res.data
        })
      },
    })
    //获取商品数据
    var groupId= wx.getStorageSync('groupId')
    wx.request({
      url: `${app.globalData.API_URL}/goods`,
      method: 'GET',
      data:{},
      success: function (res) {
        console.log(res)
        that.setData({
          goods: res.data
        })
         wx.stopPullDownRefresh()
      },
      fail: function () {
        // fail
      },
      complete: function () {
        // complete
      }
    })
  },
  // 轮播
  tap_filter: function (e) {
    if (this.data.open) {
      this.setData({
        open: false,
        display: "none"
      });
    } else {
      this.setData({
        open: true,
        display: "block"
      });
    }
  }
})