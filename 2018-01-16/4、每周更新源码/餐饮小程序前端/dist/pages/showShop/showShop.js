'use strict';

// 获取全局应用程序实例对象
// const app = getApp()

// 创建页面实例对象
Page({
  /**
   * 页面的初始数据
   */
  data: {
    title: '排队取号',
    nearShop: [{
      img: 'http://img02.tooopen.com/images/20150928/tooopen_sy_143912755726.jpg',
      name: '青花椒砂锅鱼',
      price: '30',
      kind: '中国菜',
      distance: '8.6km',
      status: '无需排队',
      grade: 'five-star'
    }, {
      img: 'http://img02.tooopen.com/images/20150928/tooopen_sy_143912755726.jpg',
      name: '青花椒砂锅鱼',
      price: '30',
      kind: '中国菜',
      status: '无需排队',
      grade: 'four-star'
    }, {
      img: 'http://img02.tooopen.com/images/20150928/tooopen_sy_143912755726.jpg',
      name: '青花椒砂锅鱼',
      price: '128',
      kind: '中国菜',
      status: '无需排队',
      grade: 'one-star'
    }, {
      img: 'http://img02.tooopen.com/images/20150928/tooopen_sy_143912755726.jpg',
      name: '青花椒砂锅鱼',
      price: '128',
      kind: '中国菜',
      status: '无需排队',
      grade: 'one-star'
    }, {
      img: 'http://img02.tooopen.com/images/20150928/tooopen_sy_143912755726.jpg',
      name: '青花椒砂锅鱼',
      price: '128',
      kind: '中国菜',
      status: '无需排队',
      grade: 'one-star'
    }, {
      img: 'http://img02.tooopen.com/images/20150928/tooopen_sy_143912755726.jpg',
      name: '青花椒砂锅鱼',
      price: '128',
      kind: '中国菜',
      status: '无需排队',
      grade: 'one-star'
    }, {
      img: 'http://img02.tooopen.com/images/20150928/tooopen_sy_143912755726.jpg',
      name: '青花椒砂锅鱼',
      price: '128',
      kind: '中国菜',
      status: '无需排队',
      grade: 'one-star'
    }, {
      img: 'http://img02.tooopen.com/images/20150928/tooopen_sy_143912755726.jpg',
      name: '青花椒砂锅鱼',
      price: '128',
      kind: '中国菜',
      status: '无需排队',
      grade: 'one-star'
    }]
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function onLoad(e) {
    // console.log(e)
    if (e.type === '1') {
      wx.setNavigationBarTitle({
        title: '预约取号'
      });
      this.setData({
        title: '预约订座'
      });
    }
    // TODO: onLoad
  },


  /**
   * 生命周期函数--监听页面初次渲染完成
   */
  onReady: function onReady() {
    // TODO: onReady
  },


  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function onShow() {
    // TODO: onShow
  },


  /**
   * 生命周期函数--监听页面隐藏
   */
  onHide: function onHide() {
    // TODO: onHide
  },


  /**
   * 生命周期函数--监听页面卸载
   */
  onUnload: function onUnload() {
    // TODO: onUnload
  },


  /**
   * 页面相关事件处理函数--监听用户下拉动作
   */
  onPullDownRefresh: function onPullDownRefresh() {
    // TODO: onPullDownRefresh
  }
});
//# sourceMappingURL=showShop.js.map
