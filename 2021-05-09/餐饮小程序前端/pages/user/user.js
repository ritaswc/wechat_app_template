'use strict';

// 获取全局应用程序实例对象
var app = getApp();

// 创建页面实例对象
Page({
  /**
   * 页面的初始数据
   */
  data: {
    title: 'user',
    userInfo: null,
    userDetail: [{
      title: '正在排队',
      number: 1
    }, {
      title: '优惠券',
      number: 4
    }, {
      title: '积分',
      number: 20
    }],
    userList: [{
      icon: 'iconfont icon-xiaoxi',
      title: '我的排单号',
      id: 'number'
    }, {
      icon: 'iconfont icon-lingdang',
      title: '消息',
      id: 'message'
    }, {
      icon: 'iconfont icon-fapiao',
      title: '积分兑换',
      id: 'integral'
    }, {
      icon: 'iconfont icon-dingdan',
      title: '我的订单',
      id: 'order'
    }, {
      icon: 'iconfont icon-iconfontruzhu-copy',
      title: '商家入驻',
      id: 'merchant'
    }]
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function onLoad() {
    this.setData({
      userInfo: app.data.userInfo
    });
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
//# sourceMappingURL=user.js.map
