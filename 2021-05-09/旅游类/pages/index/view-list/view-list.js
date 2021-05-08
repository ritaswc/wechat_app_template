// pages/index/view-list/view-list.js
import { View, star, getViewData } from "../../../utils/util.js"
Page({
  data: {
    viewList: [],
    showMore: false
  },
  onLoad: function (options) {
    let allCity = JSON.parse(options.allcity).allcity;
    let _this = this;
    // 页面初始化 options为页面跳转所带来的参数
    // 此处模拟数据为全部景点,真实项目应该带参数查询加载更多！
    getViewData(allCity, '北京', function (res) {
      _this.setData({
        viewList: res
      })
    })
  },
  lower() {
    this.setData({
      showMore: true
    });
    wx.showNavigationBarLoading();
  },
  scroll() {
    if (this.data.showMore) {
      this.setData({
        showMore: false
      })
   wx.hideNavigationBarLoading();
    }
  },
   // 进入景点详情
  enterDetail(e) {
    let sid=e.currentTarget.dataset.id;
    wx.navigateTo({
      url: '../view-detail/view-detail?sid='+sid+''
    })
  }
})