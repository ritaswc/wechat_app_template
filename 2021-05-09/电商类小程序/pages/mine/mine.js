Page({
  data: {
    userInfo:'',
    phonedata:''
  },
  onLoad: function (options) {
    // 页面初始化 options为页面跳转所带来的参数
    var that=this;
    var userInfo=wx.getStorageSync('login')
    that.setData({
      userInfo:userInfo
    })
    var phone=wx.getStorageSync('phone')
    console.log(phone)
    that.setData({
      phonedata:phone
    })
  },
  // 编辑资料
  onEditTap: function (e) {
    wx.navigateTo({
      url: "../edit/edit"
    })
  },
  // 会员注册
  onRegTap: function (e) {
    wx.navigateTo({
      url: "../login/register/register"
    })
  },
  // 我的订单
  tap_order: function (e) {
    wx.navigateTo({
      url: "../order/order"
    })
  },
  // 我的收藏
  tap_collect: function (e) {
    wx.navigateTo({
      url: "../login/login"
    })
  },
  // 联系地址
  tap_address: function (e) {
    wx.navigateTo({
      url: "../address/user-address/user-address"
    })
  },
  // 商品管理
  tap_mallManage: function (e) {
     wx.navigateTo({
      url: "../collect/collect"
    })
  },//提交商品
  tap_addgoods: function (e) {
    wx.navigateTo({
      url: "../addgoods/addgoods"
    })
  },//审核店铺
  tap_review: function (e) {
    wx.navigateTo({
      url: "../review/review"
    })
  }
})