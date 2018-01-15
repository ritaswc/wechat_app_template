// pages/addresslist/addresslist.js
Page({
  data: {
    addressList: [],
  },

  //添加收货地址
  addAdress: function () {
    wx.navigateTo({
      url: '../../pages/address/address',

    })
  },
  //选择收货地址
  selectOtherAddress: function (e) {
    var selectIndex = e.currentTarget.dataset.index;
    getApp().globalData.otherAddressInfo = this.data.addressList[selectIndex];
    wx.navigateBack({
      delta: 1, // 回退前 delta(默认为1) 页面
    })
  },
  onLoad: function (options) {
    // 页面初始化 options为页面跳转所带来的参数

  },
  onReady: function () {
    // 页面渲染完成
  },
  onShow: function () {
    // 页面显示
    this.setData({
      addressList: wx.getStorageSync('address'),
    });
  },
  onHide: function () {
    // 页面隐藏
  },
  onUnload: function () {
    // 页面关闭
  }
})