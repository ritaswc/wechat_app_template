//coupon.js
var uri = 'couponApi/couponMemberList'
var request = require('../../utils/https.js')
Page({
  data: {
    tips: '无数据',
    navTab: ["待使用", "已过期", "已使用"],
    currentNavtab: 0,
    couponNum: 0, //待使用0，已使用1，过期2
    hidden: false,
    list: []
  },
  onLoad: function (options) {
    // 生命周期函数--监听页面加载
    this.setData({ currentNavtab: 0 });
    this.getData();


  },
  getData: function () {
    var that = this;
    var couponNum = that.data.couponNum;
    request.req(uri, {
      couponIsUser: couponNum
    }, (err, res) => {
      console.log(res.data.result)
      if (res.data.result === 1) {
        that.setData({ hidden: true })
        if (!res.data) {   //无数据
          that.setData({ tips: "没有数据~" })
        } else {
          //todo

        }
      }
    })
  },
  switchTab: function (o) {
    var that = this;
    var idx = o.currentTarget.dataset.idx;
    if (idx !== that.data.currentNavtab) {
      that.setData({
        currentNavtab: idx,
        list: []
      })
      that.getData();
    }
  },
  onShareAppMessage: function () {
    // 用户点击右上角分享
    return {
      title: 'title', // 分享标题
      desc: 'desc', // 分享描述
      path: 'path' // 分享路径
    }
  }
})