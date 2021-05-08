var event = require('../../utils/event')
var util=require('../../utils/util')
var threedays=util.getWeek(3)
Page({
  data: {
    activeindex: 2,
    shop: {},
    imgUrl: '',
    activemovie:{},
    threedays:threedays,
    theday:0
  },
  onLoad: function (options) {
    var that = this
    
    console.log('shop-onLoad')
    wx.request({
      url: 'http://localhost:8888/shop/',
      success: function (res) {
        console.log(res.data.data)
        var shop = res.data.data
        var imgUrl = shop.movies[2].cover
        var activemovie=shop.movies[2]
        that.setData({
          shop: shop,
          imgUrl: imgUrl,
          activemovie:activemovie
        })
      }
    })
  },
  openMap: function () {
    var that = this
    wx.openLocation({
      latitude: 31, // 纬度，范围为-90~90，负数表示南纬
      longitude: 14, // 经度，范围为-180~180，负数表示西经
      scale: 28, // 缩放比例
      // name: '我家', // 位置名
      // address: '洼的地方', // 地址的详细说明
      success: function (res) {
        // success
      },
      fail: function () {
        // fail
      },
      complete: function () {
        // complete
      }
    })
  },
  tapselect: function (e) {
    console.log(e.currentTarget.id)
    var that = this
    var activeindex = Number(e.currentTarget.id)
    this.setData({
      imgUrl: that.data.shop.movies[activeindex].cover,
      activeindex: activeindex,
      activemovie:that.data.shop.movies[activeindex]
    })
  },
  selectDate:function(e){
var theday=e.currentTarget.id
this.setData({
  theday:theday
})
  }
})